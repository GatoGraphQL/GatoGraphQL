<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\Interface;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups;
use PoP\ComponentModel\Facades\AttachableExtensions\AttachableExtensionManagerFacade;
use PoP\ComponentModel\FieldInterfaceResolvers\FieldInterfaceResolverInterface;
use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use PoP\ComponentModel\TypeResolvers\Interface\InterfaceTypeResolverInterface;

abstract class AbstractInterfaceTypeResolver extends AbstractTypeResolver implements InterfaceTypeResolverInterface
{
    /**
     * @var array<string, FieldInterfaceResolverInterface[]>|null
     */
    protected ?array $fieldInterfaceResolversByField = null;
    /**
     * @var string[]|null
     */
    protected ?array $fieldInterfaceResolverClasses = null;
    /**
     * @var string[]|null
     */
    protected ?array $fieldNamesToImplement = null;

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
        foreach ($this->getAllFieldInterfaceResolvers() as $fieldInterfaceResolver) {
            $fieldNamesToImplement = array_merge(
                $fieldNamesToImplement,
                $fieldInterfaceResolver->getFieldNamesToImplement()
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
        foreach ($this->getAllFieldInterfaceResolvers() as $fieldInterfaceResolver) {
            $implementedFieldInterfaceResolverClasses = array_merge(
                $implementedFieldInterfaceResolverClasses,
                $fieldInterfaceResolver->getImplementedFieldInterfaceResolverClasses()
            );
        }
        $implementedFieldInterfaceResolverClasses = array_values(array_unique($implementedFieldInterfaceResolverClasses));
        /** @var FieldInterfaceResolverInterface[] */
        $implementedFieldInterfaceResolvers = array_map(
            fn (string $fieldInterfaceResolverClass) => $this->instanceManager->getInstance($fieldInterfaceResolverClass),
            $implementedFieldInterfaceResolverClasses
        );
        $implementedInterfaceTypeResolverClasses = [];
        foreach ($implementedFieldInterfaceResolvers as $implementedFieldInterfaceResolver) {
            $implementedInterfaceTypeResolverClasses = array_merge(
                $implementedInterfaceTypeResolverClasses,
                $implementedFieldInterfaceResolver->getInterfaceTypeResolverClasses()
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
     * @return FieldInterfaceResolverInterface[]
     */
    public function getAllFieldInterfaceResolvers(): array
    {
        return array_map(
            fn (string $fieldInterfaceResolverClass) => $this->instanceManager->getInstance($fieldInterfaceResolverClass),
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
            foreach ($this->getAllFieldInterfaceResolverClassesByField() as $fieldName => $fieldInterfaceResolverClasses) {
                $this->fieldInterfaceResolverClasses = array_merge(
                    $this->fieldInterfaceResolverClasses,
                    $fieldInterfaceResolverClasses
                );
            }
            $this->fieldInterfaceResolverClasses = array_values(array_unique($this->fieldInterfaceResolverClasses));
        }
        return $this->fieldInterfaceResolverClasses;
    }

    /**
     * Produce an array of all the interface's fieldNames and, for each,
     * a list of all the FieldResolverInterface classes
     * 
     * @return array<string, string[]>
     */
    final public function getAllFieldInterfaceResolverClassesByField(): array
    {
        return array_map(
            fn (array $fieldInterfaceResolvers) => array_map('get_class', $fieldInterfaceResolvers),
            $this->getAllFieldInterfaceResolversByField()
        );
    }

    /**
     * Produce an array of all the interface's fieldNames and, for each,
     * a list of all the FieldResolverInterfaces
     * 
     * @return array<string, FieldInterfaceResolverInterface[]>
     */
    final public function getAllFieldInterfaceResolversByField(): array
    {
        if ($this->fieldInterfaceResolversByField === null) {
            $this->fieldInterfaceResolversByField = $this->calculateAllFieldInterfaceResolversByField();
        }
        return $this->fieldInterfaceResolversByField;
    }

    /**
     * Produce an array of all the interface's fieldNames and, for each,
     * a list of all the FieldResolverInterfaces
     * 
     * @return array<string, FieldInterfaceResolverInterface[]>
     */
    protected function calculateAllFieldInterfaceResolversByField(): array
    {
        $attachableExtensionManager = AttachableExtensionManagerFacade::getInstance();
        $fieldInterfaceResolversByField = [];

        // Get the FieldInterfaceResolvers attached to this InterfaceTypeResolver
        // and to all the interfaces it implements
        $classStack = [
            get_called_class(),
        ];
        while (!empty($classStack)) {
            $class = array_shift($classStack);
            // Iterate classes from the current class towards the parent classes until finding typeResolver that satisfies processing this field
            do {
                /** @var FieldInterfaceResolverInterface[] */
                $attachedFieldInterfaceResolvers = $attachableExtensionManager->getAttachedExtensions($class, AttachableExtensionGroups::FIELDINTERFACERESOLVERS);
                foreach ($attachedFieldInterfaceResolvers as $fieldInterfaceResolver) {
                    // Process the fields which have not been processed yet
                    $extensionFieldNames = $fieldInterfaceResolver->getFieldNamesToImplement();
                    foreach (array_diff($extensionFieldNames, array_keys($fieldInterfaceResolversByField)) as $fieldName) {
                        $fieldInterfaceResolversByField[$fieldName] ??= [];
                        $fieldInterfaceResolversByField[$fieldName][] = $fieldInterfaceResolver;
                    }
                    // The interfaces implemented by the FieldInterfaceResolver can have, themselves, FieldInterfaceResolvers attached to them
                    $classStack = array_values(array_unique(array_merge(
                        $classStack,
                        $fieldInterfaceResolver->getImplementedFieldInterfaceResolverClasses()
                    )));
                }
                // Otherwise, continue iterating for the class parents
            } while ($class = get_parent_class($class));
        }

        return $fieldInterfaceResolversByField;
    }
}
