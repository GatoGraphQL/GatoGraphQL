<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\InterfaceType;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups;
use PoP\ComponentModel\Facades\AttachableExtensions\AttachableExtensionManagerFacade;
use PoP\ComponentModel\FieldInterfaceResolvers\InterfaceTypeFieldResolverInterface;
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
    private array $fieldNamesResolvedByFieldInterfaceResolver = [];
    /**
     * @var string[]|null
     */
    protected ?array $fieldInterfaceResolverClasses = null;

    /**
     * The list of the fieldNames to implement in the Interface,
     * collected from all the injected FieldInterfaceResolvers
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
        foreach ($this->getAllFieldInterfaceResolvers() as $interfaceTypeFieldResolver) {
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
        $implementedFieldInterfaceResolverClasses = [];
        foreach ($this->getAllFieldInterfaceResolvers() as $interfaceTypeFieldResolver) {
            $implementedFieldInterfaceResolverClasses = array_merge(
                $implementedFieldInterfaceResolverClasses,
                $interfaceTypeFieldResolver->getImplementedFieldInterfaceResolverClasses()
            );
        }
        $implementedFieldInterfaceResolverClasses = array_values(array_unique($implementedFieldInterfaceResolverClasses));
        /** @var InterfaceTypeFieldResolverInterface[] */
        $implementedFieldInterfaceResolvers = array_map(
            fn (string $interfaceTypeFieldResolverClass) => $this->instanceManager->getInstance($interfaceTypeFieldResolverClass),
            $implementedFieldInterfaceResolverClasses
        );
        $implementedInterfaceTypeResolverClasses = [];
        foreach ($implementedFieldInterfaceResolvers as $implementedFieldInterfaceResolver) {
            $implementedInterfaceTypeResolverClasses = array_merge(
                $implementedInterfaceTypeResolverClasses,
                $implementedFieldInterfaceResolver->getPartiallyImplementedInterfaceTypeResolverClasses()
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
    public function getAllFieldInterfaceResolvers(): array
    {
        return array_map(
            fn (string $interfaceTypeFieldResolverClass) => $this->instanceManager->getInstance($interfaceTypeFieldResolverClass),
            $this->getAllFieldInterfaceResolverClasses()
        );
    }

    /**
     * Produce an array of all the attached FieldResolverInterfaces
     *
     * @return string[]
     */
    public function getAllFieldInterfaceResolverClasses(): array
    {
        if ($this->fieldInterfaceResolverClasses === null) {
            $this->fieldInterfaceResolverClasses = [];
            foreach ($this->getAllFieldInterfaceResolverClassesByField() as $fieldName => $interfaceTypeFieldResolverClasses) {
                $this->fieldInterfaceResolverClasses = array_merge(
                    $this->fieldInterfaceResolverClasses,
                    $interfaceTypeFieldResolverClasses
                );
            }
            $this->fieldInterfaceResolverClasses = array_values(array_unique($this->fieldInterfaceResolverClasses));
        }
        return $this->fieldInterfaceResolverClasses;
    }

    /**
     * Produce an array of all the interface's fieldNames and, for each,
     * a list of all the FieldInterfaceResolver classes
     *
     * @return array<string, string[]>
     */
    final public function getAllFieldInterfaceResolverClassesByField(): array
    {
        return array_map(
            fn (array $interfaceTypeFieldResolvers) => array_map('get_class', $interfaceTypeFieldResolvers),
            $this->getAllFieldInterfaceResolversByField()
        );
    }

    /**
     * Produce an array of all the interface's fieldNames and, for each,
     * a list of all the FieldResolverInterfaces
     *
     * @return array<string, InterfaceTypeFieldResolverInterface[]>
     */
    final public function getAllFieldInterfaceResolversByField(): array
    {
        if ($this->interfaceTypeFieldResolversByField === null) {
            $this->interfaceTypeFieldResolversByField = $this->calculateAllFieldInterfaceResolversByField();
        }
        return $this->interfaceTypeFieldResolversByField;
    }

    /**
     * Produce an array of all the interface's fieldNames and, for each,
     * a list of all the FieldResolverInterfaces
     *
     * @return array<string, InterfaceTypeFieldResolverInterface[]>
     */
    protected function calculateAllFieldInterfaceResolversByField(): array
    {
        $attachableExtensionManager = AttachableExtensionManagerFacade::getInstance();
        $interfaceTypeFieldResolversByField = [];

        // Get the FieldInterfaceResolvers attached to this InterfaceTypeResolver
        // and to all the interfaces it implements
        $classStack = [
            get_called_class(),
        ];
        while (!empty($classStack)) {
            $class = array_shift($classStack);
            // Iterate classes from the current class towards the parent classes until finding typeResolver that satisfies processing this field
            do {
                /** @var InterfaceTypeFieldResolverInterface[] */
                $attachedFieldInterfaceResolvers = $attachableExtensionManager->getAttachedExtensions($class, AttachableExtensionGroups::FIELDINTERFACERESOLVERS);
                foreach ($attachedFieldInterfaceResolvers as $interfaceTypeFieldResolver) {
                    // Process the fields which have not been processed yet
                    $extensionFieldNames = $this->getFieldNamesResolvedByFieldInterfaceResolver($interfaceTypeFieldResolver);
                    foreach (array_diff($extensionFieldNames, array_keys($interfaceTypeFieldResolversByField)) as $fieldName) {
                        $interfaceTypeFieldResolversByField[$fieldName] ??= [];
                        $interfaceTypeFieldResolversByField[$fieldName][] = $interfaceTypeFieldResolver;
                    }
                    // The interfaces implemented by the FieldInterfaceResolver can have, themselves, FieldInterfaceResolvers attached to them
                    $classStack = array_values(array_unique(array_merge(
                        $classStack,
                        $interfaceTypeFieldResolver->getImplementedFieldInterfaceResolverClasses()
                    )));
                }
                // Otherwise, continue iterating for the class parents
            } while ($class = get_parent_class($class));
        }

        return $interfaceTypeFieldResolversByField;
    }

    /**
     * Return the fieldNames resolved by the fieldInterfaceResolver, adding a hook to disable each of them (eg: to implement a private schema)
     *
     * @return string[]
     */
    protected function getFieldNamesResolvedByFieldInterfaceResolver(InterfaceTypeFieldResolverInterface $interfaceTypeFieldResolver): array
    {
        $interfaceTypeFieldResolverClass = get_class($interfaceTypeFieldResolver);
        if (!isset($this->fieldNamesResolvedByFieldInterfaceResolver[$interfaceTypeFieldResolverClass])) {
            // Merge the fieldNames resolved by this field resolver class, and the interfaces it implements
            $fieldNames = $interfaceTypeFieldResolver->getFieldNamesToImplement();
            $fieldNames = $this->maybeExcludeFieldNamesFromSchema(
                $this,
                $interfaceTypeFieldResolver,
                $fieldNames
            );
            $this->fieldNamesResolvedByFieldInterfaceResolver[$interfaceTypeFieldResolverClass] = $fieldNames;
        }
        return $this->fieldNamesResolvedByFieldInterfaceResolver[$interfaceTypeFieldResolverClass];
    }
}
