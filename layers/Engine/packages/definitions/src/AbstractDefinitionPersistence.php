<?php

declare(strict_types=1);

namespace PoP\Definitions;

abstract class AbstractDefinitionPersistence implements DefinitionPersistenceInterface
{
    /**
     * @var array<string,array>
     */
    protected array $definitions = [];
    /**
     * @var array<string,array>
     */
    protected array $names = [];
    /**
     * @var array<string,array>
     */
    protected array $resolverData = [];
    protected bool $addedDefinition = false;
    /**
     * @var array<string, DefinitionResolverInterface>
     */
    protected array $definition_resolvers = [];

    public function __construct()
    {
        // Comment Leo 03/11/2017: added a DB to avoid the website from producing errors
        // each time that new templates are introduced
        // The DB is needed to return the same mangled results for the same incoming templates, over time
        // Otherwise, when a new module is introduced, the website after deploy will produce errors from
        // the cached data in the localStorage and Service Workers (the cached data references templates
        // with a name that is not the right one anymore)
        // Get the database from the file saved in disk
        // The first time we generate the database, there will be nothing
        if ($persisted_data = $this->getPersistedData()) {
            $this->definitions = $persisted_data['database']['definitions'];
            $this->names = $persisted_data['database']['names'];
            $this->resolverData = $persisted_data['resolver-data'];
        }
    }

    /**
     * @return array<string, DefinitionResolverInterface>
     */
    public function getDefinitionResolvers(): array
    {
        return $this->definition_resolvers;
    }

    public function setDefinitionResolver(DefinitionResolverInterface $definition_resolver, string $group): void
    {
        $this->definition_resolvers[$group] = $definition_resolver;
        $definition_resolver->setPersistedData($this->resolverData[$group] ?? []);
    }

    public function getDefinitionResolver(string $group): ?DefinitionResolverInterface
    {
        return $this->definition_resolvers[$group];
    }

    public function getSavedDefinition(string $name, string $group): ?string
    {
        if ($definition = $this->definitions[$group][$name] ?? null) {
            return $definition;
        }

        return null;
    }

    public function getOriginalName(string $definition, string $group): ?string
    {
        if ($name = $this->names[$group][$definition] ?? null) {
            return $name;
        }

        return null;
    }

    public function saveDefinition(string $definition, string $name, string $group): void
    {
        $this->definitions[$group][$name] = $definition;
        $this->names[$group][$definition] = $name;

        // If that definition is not cached, it is a new one that will need to be saved
        $this->addedDefinition = true;
    }

    /**
     * @return array<string,mixed>
     */
    protected function getDatabase(): array
    {
        return [
            'definitions' => $this->definitions,
            'names' => $this->names,
        ];
    }

    protected function addedDefinition(): bool
    {
        return $this->addedDefinition;
    }

    public function storeDefinitionsPersistently(): void
    {
        if (Environment::disableDefinitionPersistence()) {
            return;
        }
        if ($this->addedDefinition()) {
            // Save the DB in the hard disk
            $data = array(
                'database' => $this->getDatabase(),
                'resolver-data' => [],
            );
            foreach ($this->getDefinitionResolvers() as $group => $resolver) {
                $data['resolver-data'][$group] = $resolver->getDataToPersist();
            }
            $this->persist($data);
        }
    }

    /**
     * @return array<string,mixed>
     */
    abstract protected function getPersistedData(): array;
    /**
     * @param array<string,mixed> $data
     */
    abstract protected function persist(array $data): void;
}
