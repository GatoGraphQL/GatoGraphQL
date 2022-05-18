<?php

declare(strict_types=1);

namespace PoP\Definitions;

use PoP\Definitions\Configuration\Request;

class DefinitionManager implements DefinitionManagerInterface
{
    /**
     * @var array<string, array>
     */
    protected array $names = [];
    /**
     * @var array<string, array>
     */
    protected array $name_definitions = [];
    /**
     * @var array<string, array>
     */
    protected array $definition_names = [];
    /**
     * @var array<string, DefinitionResolverInterface>
     */
    protected array $definition_resolvers = [];
    private ?DefinitionPersistenceInterface $definition_persistence = null;

    public function isEnabled(): bool
    {
        return !Environment::disableDefinitions() && Request::isMangled();
    }

    /**
     * @return array<string, DefinitionResolverInterface>
     */
    public function getDefinitionResolvers(): array
    {
        if (!$this->isEnabled()) {
            return [];
        }
        return $this->definition_resolvers;
    }
    public function getDefinitionResolver(string $group): ?DefinitionResolverInterface
    {
        if (!$this->isEnabled()) {
            return null;
        }
        return $this->definition_resolvers[$group] ?? null;
    }
    public function setDefinitionResolver(DefinitionResolverInterface $definition_resolver, string $group): void
    {
        $this->definition_resolvers[$group] = $definition_resolver;

        // Allow the Resolver and the Persistence to talk to each other
        if ($this->definition_persistence) {
            $this->definition_persistence->setDefinitionResolver($definition_resolver, $group);
        }
    }
    public function getDefinitionPersistence(): ?DefinitionPersistenceInterface
    {
        if (!$this->isEnabled()) {
            return null;
        }
        return $this->definition_persistence;
    }
    public function setDefinitionPersistence(DefinitionPersistenceInterface $definition_persistence): void
    {
        $this->definition_persistence = $definition_persistence;

        // Allow the Resolver and the Persistence to talk to each other
        foreach ($this->definition_resolvers as $group => $definition_resolver) {
            $this->definition_persistence->setDefinitionResolver($definition_resolver, $group);
        }
    }

    /**
     * Function used to create a definition for a module.
     * Needed for reducing the filesize of the html generated for PROD
     * Instead of using the name of the $component, we use a unique number in base 36,
     * so the name will occupy much lower size
     * Comment Leo 27/09/2017: Changed from $component to only $id so that it can also
     * be used with ResourceLoaders
     */
    public function getDefinition(string $name, string $group): string
    {
        if ($definition = (isset($this->name_definitions[$group]) ? $this->name_definitions[$group][$name] : null)) {
            return $definition;
        }

        // Allow the persistence layer to return the value directly
        $definitionPersistence = $this->getDefinitionPersistence();
        if ($definitionPersistence) {
            if ($definition = $definitionPersistence->getSavedDefinition($name, $group)) {
                $this->definition_names[$group][$definition] = $name;
                $this->name_definitions[$group][$name] = $definition;
                return $definition;
            }
        }

        // Allow the injected Resolver to decide how the name is resolved
        if ($definitionResolver = $this->getDefinitionResolver($group)) {
            $definition = $definitionResolver->getDefinition($name, $group);
            if ($definition != $name && $definitionPersistence) {
                $definitionPersistence->saveDefinition($definition, $name, $group);
            }
            $this->definition_names[$group][$definition] = $name;
            $this->name_definitions[$group][$name] = $definition;
            return $definition;
        }

        return $name;
    }

    /**
     * Given a definition, retrieve its original name
     */
    public function getOriginalName(string $definition, string $group): string
    {
        // If it is cached in this object, return it already
        if (isset($this->definition_names[$group][$definition])) {
            return $this->definition_names[$group][$definition];
        }

        // Otherwise, ask if the persistence object has it
        if ($definitionPersistence = $this->getDefinitionPersistence()) {
            if ($name = $definitionPersistence->getOriginalName($definition, $group)) {
                $this->definition_names[$group][$definition] = $name;
                $this->name_definitions[$group][$name] = $definition;
                return $name;
            }
        }

        // It didn't find it, assume it's the same
        return $definition;
    }

    public function maybeStoreDefinitionsPersistently(): void
    {
        if ($definitionPersistence = $this->getDefinitionPersistence()) {
            $definitionPersistence->storeDefinitionsPersistently();
        }
    }
}
