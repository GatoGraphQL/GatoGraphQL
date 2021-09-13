<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\InterfaceType;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups;
use PoP\ComponentModel\Facades\AttachableExtensions\AttachableExtensionManagerFacade;
use PoP\ComponentModel\InterfaceTypeFieldResolvers\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use PoP\ComponentModel\TypeResolvers\ExcludeFieldNamesFromSchemaTypeResolverTrait;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;

abstract class AbstractInterfaceTypeResolver extends AbstractTypeResolver implements InterfaceTypeResolverInterface
{
    use ExcludeFieldNamesFromSchemaTypeResolverTrait;

    /**
     * @var array<string, InterfaceTypeFieldResolverInterface[]>|null
     */
    protected ?array $interfaceTypeFieldResolversByField = null;
    /**
     * @var string[]|null
     */
    protected ?array $fieldNamesToImplement = null;
    /**
     * @var array<string, array>
     */
    private array $fieldNamesResolvedByInterfaceTypeFieldResolver = [];
    /**
     * @var string[]|null
     */
    protected ?array $interfaceTypeFieldResolverClasses = null;

    /**
     * The list of the fieldNames to implement in the Interface,
     * collected from all the injected InterfaceTypeFieldResolvers
     *
     * @return string[]
     */
    final public function getFieldNamesToImplement(): array
    {
        if ($this->fieldNamesToImplement === null) {
            $this->fieldNamesToImplement = $this->calculateFieldNamesToImplement();
        }
        return $this->fieldNamesToImplement;
    }

    private function calculateFieldNamesToImplement(): array
    {
        $fieldNamesToImplement = [];
        foreach ($this->getAllInterfaceTypeFieldResolvers() as $interfaceTypeFieldResolver) {
            $fieldNamesToImplement = array_merge(
                $fieldNamesToImplement,
                $interfaceTypeFieldResolver->getFieldNamesToImplement()
            );
        }
        return array_values(array_unique($fieldNamesToImplement));
    }

    /**
     * Interfaces "partially" implemented by this Interface
     *
     * @return string[]
     */
    public function getPartiallyImplementedInterfaceTypeResolverClasses(): array
    {
        $implementedInterfaceTypeFieldResolverClasses = [];
        foreach ($this->getAllInterfaceTypeFieldResolvers() as $interfaceTypeFieldResolver) {
            $implementedInterfaceTypeFieldResolverClasses = array_merge(
                $implementedInterfaceTypeFieldResolverClasses,
                $interfaceTypeFieldResolver->getImplementedInterfaceTypeFieldResolverClasses()
            );
        }
        $implementedInterfaceTypeFieldResolverClasses = array_values(array_unique($implementedInterfaceTypeFieldResolverClasses));
        /** @var InterfaceTypeFieldResolverInterface[] */
        $implementedInterfaceTypeFieldResolvers = array_map(
            fn (string $interfaceTypeFieldResolverClass) => $this->instanceManager->getInstance($interfaceTypeFieldResolverClass),
            $implementedInterfaceTypeFieldResolverClasses
        );
        $implementedInterfaceTypeResolverClasses = [];
        foreach ($implementedInterfaceTypeFieldResolvers as $implementedInterfaceTypeFieldResolver) {
            $implementedInterfaceTypeResolverClasses = array_merge(
                $implementedInterfaceTypeResolverClasses,
                $implementedInterfaceTypeFieldResolver->getPartiallyImplementedInterfaceTypeResolverClasses()
            );
        }
        return array_values(array_unique($implementedInterfaceTypeResolverClasses));
    }

    /**
     * Interfaces "partially" implemented by this Interface
     *
     * @return InterfaceTypeResolverInterface[]
     */
    public function getPartiallyImplementedInterfaceTypeResolvers(): array
    {
        return array_map(
            fn (string $interfaceTypeResolverClass) => $this->instanceManager->getInstance($interfaceTypeResolverClass),
            $this->getPartiallyImplementedInterfaceTypeResolverClasses()
        );
    }

    /**
     * Produce an array of all the attached FieldResolverInterfaces
     *
     * @return InterfaceTypeFieldResolverInterface[]
     */
    public function getAllInterfaceTypeFieldResolvers(): array
    {
        return array_map(
            fn (string $interfaceTypeFieldResolverClass) => $this->instanceManager->getInstance($interfaceTypeFieldResolverClass),
            $this->getAllInterfaceTypeFieldResolverClasses()
        );
    }

    /**
     * Produce an array of all the attached FieldResolverInterfaces
     *
     * @return string[]
     */
    public function getAllInterfaceTypeFieldResolverClasses(): array
    {
        if ($this->interfaceTypeFieldResolverClasses === null) {
            $this->interfaceTypeFieldResolverClasses = [];
            foreach ($this->getAllInterfaceTypeFieldResolverClassesByField() as $fieldName => $interfaceTypeFieldResolverClasses) {
                $this->interfaceTypeFieldResolverClasses = array_merge(
                    $this->interfaceTypeFieldResolverClasses,
                    $interfaceTypeFieldResolverClasses
                );
            }
            $this->interfaceTypeFieldResolverClasses = array_values(array_unique($this->interfaceTypeFieldResolverClasses));
        }
        return $this->interfaceTypeFieldResolverClasses;
    }

    /**
     * Produce an array of all the interface's fieldNames and, for each,
     * a list of all the InterfaceTypeFieldResolver classes
     *
     * @return array<string, string[]>
     */
    final public function getAllInterfaceTypeFieldResolverClassesByField(): array
    {
        return array_map(
            fn (array $interfaceTypeFieldResolvers) => array_map('get_class', $interfaceTypeFieldResolvers),
            $this->getAllInterfaceTypeFieldResolversByField()
        );
    }

    /**
     * Produce an array of all the interface's fieldNames and, for each,
     * a list of all the FieldResolverInterfaces
     *
     * @return array<string, InterfaceTypeFieldResolverInterface[]>
     */
    final public function getAllInterfaceTypeFieldResolversByField(): array
    {
        if ($this->interfaceTypeFieldResolversByField === null) {
            $this->interfaceTypeFieldResolversByField = $this->calculateAllInterfaceTypeFieldResolversByField();
        }
        return $this->interfaceTypeFieldResolversByField;
    }

    /**
     * Produce an array of all the interface's fieldNames and, for each,
     * a list of all the FieldResolverInterfaces
     *
     * @return array<string, InterfaceTypeFieldResolverInterface[]>
     */
    protected function calculateAllInterfaceTypeFieldResolversByField(): array
    {
        $attachableExtensionManager = AttachableExtensionManagerFacade::getInstance();
        $interfaceTypeFieldResolversByField = [];

        // Get the InterfaceTypeFieldResolvers attached to this InterfaceTypeResolver
        // and to all the interfaces it implements
        $classStack = [
            get_called_class(),
        ];
        while (!empty($classStack)) {
            $class = array_shift($classStack);
            // Iterate classes from the current class towards the parent classes until finding typeResolver that satisfies processing this field
            do {
                /** @var InterfaceTypeFieldResolverInterface[] */
                $attachedInterfaceTypeFieldResolvers = $attachableExtensionManager->getAttachedExtensions($class, AttachableExtensionGroups::FIELDINTERFACERESOLVERS);
                foreach ($attachedInterfaceTypeFieldResolvers as $interfaceTypeFieldResolver) {
                    // Process the fields which have not been processed yet
                    $extensionFieldNames = $this->getFieldNamesResolvedByInterfaceTypeFieldResolver($interfaceTypeFieldResolver);
                    foreach (array_diff($extensionFieldNames, array_keys($interfaceTypeFieldResolversByField)) as $fieldName) {
                        $interfaceTypeFieldResolversByField[$fieldName] ??= [];
                        $interfaceTypeFieldResolversByField[$fieldName][] = $interfaceTypeFieldResolver;
                    }
                    // The interfaces implemented by the InterfaceTypeFieldResolver can have, themselves, InterfaceTypeFieldResolvers attached to them
                    $classStack = array_values(array_unique(array_merge(
                        $classStack,
                        $interfaceTypeFieldResolver->getImplementedInterfaceTypeFieldResolverClasses()
                    )));
                }
                // Otherwise, continue iterating for the class parents
            } while ($class = get_parent_class($class));
        }

        return $interfaceTypeFieldResolversByField;
    }

    /**
     * Return the fieldNames resolved by the interfaceTypeFieldResolver, adding a hook to disable each of them (eg: to implement a private schema)
     *
     * @return string[]
     */
    protected function getFieldNamesResolvedByInterfaceTypeFieldResolver(InterfaceTypeFieldResolverInterface $interfaceTypeFieldResolver): array
    {
        $interfaceTypeFieldResolverClass = get_class($interfaceTypeFieldResolver);
        if (!isset($this->fieldNamesResolvedByInterfaceTypeFieldResolver[$interfaceTypeFieldResolverClass])) {
            // Merge the fieldNames resolved by this field resolver class, and the interfaces it implements
            $fieldNames = $interfaceTypeFieldResolver->getFieldNamesToImplement();
            $fieldNames = $this->maybeExcludeFieldNamesFromSchema(
                $this,
                $interfaceTypeFieldResolver,
                $fieldNames
            );
            $this->fieldNamesResolvedByInterfaceTypeFieldResolver[$interfaceTypeFieldResolverClass] = $fieldNames;
        }
        return $this->fieldNamesResolvedByInterfaceTypeFieldResolver[$interfaceTypeFieldResolverClass];
    }
}
