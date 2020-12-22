<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\SchemaConfigurators;

use GraphQLAPI\GraphQLAPI\General\BlockConstants;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\Facades\Registries\TypeRegistryFacade;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\Facades\Registries\DirectiveRegistryFacade;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\Facades\Registries\FieldInterfaceRegistryFacade;
use PoP\ComponentModel\FieldInterfaceResolvers\FieldInterfaceResolverInterface;

/**
 * Base class for configuring the persisted GraphQL query before its execution
 */
abstract class AbstractGraphQLQueryConfigurator implements SchemaConfiguratorInterface
{
    /**
     * Keep a map of all namespaced type names to their resolver classes
     * @var array<string, array>|null
     */
    protected ?array $namespacedTypeNameClasses = null;
    /**
     * Keep a map of all namespaced field interface names to their resolver classes
     * @var array<string, array>|null
     */
    protected ?array $namespacedFieldInterfaceNameClasses = null;
    /**
     * Keep a map of all directives names to their resolver classes
     * @var array<string, array>|null
     */
    protected ?array $directiveNameClasses = null;

    /**
     * Lazy load and return the `$namespacedTypeNameClasses` array
     *
     * @return array<string, array>
     */
    protected function getNamespacedTypeNameClasses(): array
    {
        if (is_null($this->namespacedTypeNameClasses)) {
            $this->initNamespacedTypeNameClasses();
        }
        return (array)$this->namespacedTypeNameClasses;
    }

    /**
     * Lazy load and return the `$namespacedTypeNameClasses` array
     *
     * @return array<string, array>
     */
    protected function getNamespacedFieldInterfaceNameClasses(): array
    {
        if (is_null($this->namespacedFieldInterfaceNameClasses)) {
            $this->initNamespacedFieldInterfaceNameClasses();
        }
        return (array)$this->namespacedFieldInterfaceNameClasses;
    }

    /**
     * Initialize the `$namespacedTypeNameClasses` array
     */
    protected function initNamespacedTypeNameClasses(): void
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        $typeRegistry = TypeRegistryFacade::getInstance();
        // For each class, obtain its namespacedTypeName
        $typeResolverClasses = $typeRegistry->getTypeResolverClasses();
        $this->namespacedTypeNameClasses = [];
        foreach ($typeResolverClasses as $typeResolverClass) {
            /**
             * @var TypeResolverInterface
             */
            $typeResolver = $instanceManager->getInstance($typeResolverClass);
            $typeResolverNamespacedName = $typeResolver->getNamespacedTypeName();
            $this->namespacedTypeNameClasses[$typeResolverNamespacedName] = $typeResolverClass;
        }
    }

    /**
     * Initialize the `$namespacedTypeNameClasses` array
     */
    protected function initNamespacedFieldInterfaceNameClasses(): void
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        $fieldInterfaceRegistry = FieldInterfaceRegistryFacade::getInstance();
        // For each interface, obtain its namespacedInterfaceName
        $fieldInterfaceResolverClasses = $fieldInterfaceRegistry->getFieldInterfaceResolverClasses();
        $this->namespacedFieldInterfaceNameClasses = [];
        foreach ($fieldInterfaceResolverClasses as $fieldInterfaceResolverClass) {
            /**
             * @var FieldInterfaceResolverInterface
             */
            $fieldInterfaceResolver = $instanceManager->getInstance($fieldInterfaceResolverClass);
            $fieldInterfaceResolverNamespacedName = $fieldInterfaceResolver->getNamespacedInterfaceName();
            $this->namespacedFieldInterfaceNameClasses[$fieldInterfaceResolverNamespacedName] = $fieldInterfaceResolverClass;
        }
    }

    /**
     * Lazy load and return the `$directiveNameClasses` array
     *
     * @return array<string, array>
     */
    protected function getDirectiveNameClasses(): array
    {
        if (is_null($this->directiveNameClasses)) {
            $this->initDirectiveNameClasses();
        }
        return (array)$this->directiveNameClasses;
    }
    /**
     * Initialize the `$directiveNameClasses` array
     */
    protected function initDirectiveNameClasses(): void
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        $directiveRegistry = DirectiveRegistryFacade::getInstance();
        $directiveResolverClasses = $directiveRegistry->getDirectiveResolverClasses();
        // For each class, obtain its directive name. Notice that different directives
        // can have the same name (eg: @translate as implemented for Google and Azure),
        // then the mapping goes from name to list of resolvers
        $this->directiveNameClasses = [];
        foreach ($directiveResolverClasses as $directiveResolverClass) {
            /**
             * @var DirectiveResolverInterface
             */
            $directiveResolver = $instanceManager->getInstance($directiveResolverClass);
            $directiveResolverName = $directiveResolver->getDirectiveName();
            $this->directiveNameClasses[$directiveResolverName][] = $directiveResolverClass;
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
     * @param mixed $value
     * @return array<array> The list of entries, where an entry is an array [$typeResolverClass, $field, $value]
     */
    protected function getEntriesFromField(string $selectedField, $value): array
    {
        $namespacedTypeNameClasses = $this->getNamespacedTypeNameClasses();
        // The field is composed by the type namespaced name, and the field name, separated by "."
        // Extract these values
        $entry = explode(BlockConstants::TYPE_FIELD_SEPARATOR_FOR_DB, $selectedField);
        // Maybe the namespaced name corresponds to a type, maybe to an interface
        $maybeNamespacedTypeName = $entry[0];
        $maybeNamespacedFieldInterfaceName = $entry[0];
        $field = $entry[1];
        // From the type, obtain which resolver class processes it
        if ($typeResolverClass = $namespacedTypeNameClasses[$maybeNamespacedTypeName] ?? null) {
            // Check `getConfigurationEntries` to understand format of each entry
            return [
                [$typeResolverClass, $field, $value],
            ];
        }
        // If it is an interface, add all the types implementing that interface!
        $namespacedFieldInterfaceNameClasses = $this->getNamespacedFieldInterfaceNameClasses();
        if ($fieldInterfaceResolverClass = $namespacedFieldInterfaceNameClasses[$maybeNamespacedFieldInterfaceName] ?? null) {
            // Check `getConfigurationEntries` to understand format of each entry
            return [
                [$fieldInterfaceResolverClass, $field, $value],
            ];
        }

        return [];
    }
    /**
     * Create the service configuration entries comprising a directive and its value
     * It returns an array of arrays
     *
     * @param mixed $value
     * @return array<array> The list of entries, where an entry is an array [$directiveResolverClass, $value]
     */
    protected function getEntriesFromDirective(string $selectedDirective, $value): ?array
    {
        $directiveNameClasses = $this->getDirectiveNameClasses();
        // Obtain the directive resolver class from the directive name.
        // If more than one resolver has the same directive name, add all of them
        if ($selectedDirectiveResolverClasses = $directiveNameClasses[$selectedDirective] ?? null) {
            $entriesForDirective = [];
            foreach ($selectedDirectiveResolverClasses as $directiveResolverClass) {
                $entriesForDirective[] = [$directiveResolverClass, $value];
            }
            return $entriesForDirective;
        }
        return null;
    }
}
