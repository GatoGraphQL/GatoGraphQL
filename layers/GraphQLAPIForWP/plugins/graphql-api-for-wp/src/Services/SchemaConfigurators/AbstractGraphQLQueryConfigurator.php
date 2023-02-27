<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators;

use GraphQLAPI\GraphQLAPI\Constants\BlockConstants;
use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\SchemaConfiguratorInterface;
use PoP\ComponentModel\Constants\ConfigurationValues;
use PoP\ComponentModel\Registries\FieldDirectiveRegistryInterface;
use PoP\ComponentModel\Registries\TypeRegistryInterface;
use PoP\Root\Services\BasicServiceTrait;

/**
 * Base class for configuring the persisted GraphQL query before its execution
 */
abstract class AbstractGraphQLQueryConfigurator implements SchemaConfiguratorInterface
{
    use BasicServiceTrait;

    /**
     * Keep a map of all namespaced type names to their resolver classes
     * @var array<string,string>|null
     */
    protected ?array $namespacedObjectTypeNameResolverClasses = null;
    /**
     * Keep a map of all namespaced field interface names to their resolver classes
     * @var array<string,string>|null
     */
    protected ?array $namespacedInterfaceTypeNameResolverClasses = null;
    /**
     * Keep a map of all directives names to their resolver classes
     * @var array<string,string[]>|null
     */
    protected ?array $directiveNameClasses = null;

    private ?ModuleRegistryInterface $moduleRegistry = null;
    private ?TypeRegistryInterface $typeRegistry = null;
    private ?FieldDirectiveRegistryInterface $fieldDirectiveRegistry = null;

    final public function setModuleRegistry(ModuleRegistryInterface $moduleRegistry): void
    {
        $this->moduleRegistry = $moduleRegistry;
    }
    final protected function getModuleRegistry(): ModuleRegistryInterface
    {
        /** @var ModuleRegistryInterface */
        return $this->moduleRegistry ??= $this->instanceManager->getInstance(ModuleRegistryInterface::class);
    }
    final public function setTypeRegistry(TypeRegistryInterface $typeRegistry): void
    {
        $this->typeRegistry = $typeRegistry;
    }
    final protected function getTypeRegistry(): TypeRegistryInterface
    {
        /** @var TypeRegistryInterface */
        return $this->typeRegistry ??= $this->instanceManager->getInstance(TypeRegistryInterface::class);
    }
    final public function setFieldDirectiveRegistry(FieldDirectiveRegistryInterface $fieldDirectiveRegistry): void
    {
        $this->fieldDirectiveRegistry = $fieldDirectiveRegistry;
    }
    final protected function getFieldDirectiveRegistry(): FieldDirectiveRegistryInterface
    {
        /** @var FieldDirectiveRegistryInterface */
        return $this->fieldDirectiveRegistry ??= $this->instanceManager->getInstance(FieldDirectiveRegistryInterface::class);
    }

    /**
     * Lazy load and return the `$namespacedObjectTypeNameResolverClasses` array
     *
     * @return array<string,string>
     */
    protected function getNamespacedObjectTypeNameClasses(): array
    {
        if ($this->namespacedObjectTypeNameResolverClasses === null) {
            $this->initNamespacedObjectTypeNameClasses();
        }
        return (array)$this->namespacedObjectTypeNameResolverClasses;
    }

    /**
     * Lazy load and return the `$namespacedInterfaceTypeNameResolverClasses` array
     *
     * @return array<string,string>
     */
    protected function getNamespacedInterfaceTypeNameClasses(): array
    {
        if ($this->namespacedInterfaceTypeNameResolverClasses === null) {
            $this->initNamespacedInterfaceTypeNameClasses();
        }
        return (array)$this->namespacedInterfaceTypeNameResolverClasses;
    }

    /**
     * Initialize the `$namespacedObjectTypeNameResolverClasses` array
     */
    protected function initNamespacedObjectTypeNameClasses(): void
    {
        // For each class, obtain its namespacedTypeName
        $objectTypeResolvers = $this->getTypeRegistry()->getObjectTypeResolvers();
        $this->namespacedObjectTypeNameResolverClasses = [];
        foreach ($objectTypeResolvers as $objectTypeResolver) {
            $objectTypeResolverNamespacedName = $objectTypeResolver->getNamespacedTypeName();
            $this->namespacedObjectTypeNameResolverClasses[$objectTypeResolverNamespacedName] = $objectTypeResolver::class;
        }
    }

    /**
     * Initialize the `$namespacedObjectTypeNameResolverClasses` array
     */
    protected function initNamespacedInterfaceTypeNameClasses(): void
    {
        // For each interface, obtain its namespacedInterfaceName
        $interfaceTypeResolvers = $this->getTypeRegistry()->getInterfaceTypeResolvers();
        $this->namespacedInterfaceTypeNameResolverClasses = [];
        foreach ($interfaceTypeResolvers as $interfaceTypeResolver) {
            $interfaceTypeResolverNamespacedName = $interfaceTypeResolver->getNamespacedTypeName();
            $this->namespacedInterfaceTypeNameResolverClasses[$interfaceTypeResolverNamespacedName] = $interfaceTypeResolver::class;
        }
    }

    /**
     * Lazy load and return the `$directiveNameClasses` array
     *
     * @return array<string,string[]>
     */
    protected function getDirectiveNameClasses(): array
    {
        if ($this->directiveNameClasses === null) {
            $this->initDirectiveNameClasses();
        }
        return (array)$this->directiveNameClasses;
    }
    /**
     * Initialize the `$directiveNameClasses` array
     */
    protected function initDirectiveNameClasses(): void
    {
        $fieldDirectiveResolvers = $this->getFieldDirectiveRegistry()->getFieldDirectiveResolvers();
        // For each class, obtain its directive name. Notice that different directives
        // can have the same name (eg: @strTranslate as implemented for Google and Azure),
        // then the mapping goes from name to list of resolvers
        $this->directiveNameClasses = [];
        foreach ($fieldDirectiveResolvers as $fieldDirectiveResolver) {
            $directiveResolverName = $fieldDirectiveResolver->getDirectiveName();
            $this->directiveNameClasses[$directiveResolverName][] = $fieldDirectiveResolver::class;
        }
    }

    /**
     * Create a service configuration entry comprising a field and its value,
     * where an entry can involve a namespaced type or a namespaced interface
     *
     * It returns an array with all the entries extracted from it:
     * - If the field involves a type, the entry will be 1
     * - If the field involves an interface, the entry can be many, 1 for each type
     * implementing the interface
     *
     * @return array<mixed[]> The list of entries, where an entry is an array [$typeResolverClass, $field, $value]
     */
    protected function getEntriesFromField(string $selectedField, mixed $value): array
    {
        $namespacedObjectTypeNameResolverClasses = $this->getNamespacedObjectTypeNameClasses();
        // The field is composed by the type namespaced name, and the field name, separated by "."
        // Extract these values
        $entry = explode(BlockConstants::TYPE_FIELD_SEPARATOR_FOR_DB, $selectedField);
        // Maybe the namespaced name corresponds to a type, maybe to an interface
        $field = $entry[1];
        /**
         * Wildcard type: accept everything
         */
        if ($entry[0] === ConfigurationValues::ANY) {
            return [
                [$entry[0], $field, $value],
            ];
        }
        $maybeNamespacedObjectTypeName = $entry[0];
        $maybeNamespacedInterfaceTypeName = $entry[0];
        // From the type, obtain which resolver class processes it
        if ($objectTypeResolverClass = $namespacedObjectTypeNameResolverClasses[$maybeNamespacedObjectTypeName] ?? null) {
            // Check `getConfigurationEntries` to understand format of each entry
            return [
                [$objectTypeResolverClass, $field, $value],
            ];
        }
        // If it is an interface, add all the types implementing that interface!
        $namespacedInterfaceTypeNameResolverClasses = $this->getNamespacedInterfaceTypeNameClasses();
        if ($interfaceTypeResolverClass = $namespacedInterfaceTypeNameResolverClasses[$maybeNamespacedInterfaceTypeName] ?? null) {
            // Check `getConfigurationEntries` to understand format of each entry
            return [
                [$interfaceTypeResolverClass, $field, $value],
            ];
        }

        return [];
    }
    /**
     * Create the service configuration entries comprising a directive and its value
     * It returns an array of arrays
     *
     * @return array<mixed[]> The list of entries, where an entry is an array [$directiveResolverClass, $value]
     */
    protected function getEntriesFromDirective(string $selectedDirective, mixed $value): ?array
    {
        $directiveNameClasses = $this->getDirectiveNameClasses();
        // Obtain the directive resolver class from the directive name.
        // If more than one resolver has the same directive name, add all of them
        if ($selectedFieldDirectiveResolverClasses = $directiveNameClasses[$selectedDirective] ?? null) {
            $entriesForDirective = [];
            foreach ($selectedFieldDirectiveResolverClasses as $directiveResolverClass) {
                $entriesForDirective[] = [$directiveResolverClass, $value];
            }
            return $entriesForDirective;
        }
        return null;
    }

    public function getEnablingModule(): ?string
    {
        return null;
    }

    /**
     * Only enable the service, if the corresponding module is also enabled
     */
    public function isServiceEnabled(): bool
    {
        $enablingModule = $this->getEnablingModule();
        if ($enablingModule !== null) {
            return $this->getModuleRegistry()->isModuleEnabled($enablingModule);
        }
        return true;
    }

    /**
     * Execute the schema configuration contained in the custom post with certain ID
     */
    public function executeSchemaConfiguration(int $customPostID): void
    {
        // Only if the module is not disabled
        if (!$this->isServiceEnabled()) {
            return;
        }

        $this->doExecuteSchemaConfiguration($customPostID);
    }

    abstract protected function doExecuteSchemaConfiguration(int $customPostID): void;
}
