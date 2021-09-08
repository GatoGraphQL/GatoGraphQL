<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\Interface;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups;
use PoP\ComponentModel\Facades\AttachableExtensions\AttachableExtensionManagerFacade;
use PoP\ComponentModel\FieldInterfaceResolvers\FieldInterfaceResolverInterface;
use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;

abstract class AbstractInterfaceTypeResolver extends AbstractTypeResolver implements InterfaceTypeResolverInterface
{
    /**
     * @var array<string, FieldInterfaceResolverInterface[]>|null
     */
    protected ?array $fieldInterfaceResolvers = null;

    /**
     * Produce an array of all the interface's fieldNames and, for each,
     * a list of all the FieldResolverInterfaces
     * 
     * @return array<string, FieldInterfaceResolverInterface[]>
     */
    protected function getAllFieldInterfaceResolvers(): array
    {
        if ($this->fieldInterfaceResolvers === null) {
            $this->fieldInterfaceResolvers = $this->calculateAllFieldInterfaceResolvers();
        }
        return $this->fieldInterfaceResolvers;
    }

    /**
     * Produce an array of all the interface's fieldNames and, for each,
     * a list of all the FieldResolverInterfaces
     * 
     * @return array<string, FieldInterfaceResolverInterface[]>
     */
    protected function calculateAllFieldInterfaceResolvers(): array
    {
        $attachableExtensionManager = AttachableExtensionManagerFacade::getInstance();
        $fieldInterfaceResolvers = [];

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
                    foreach (array_diff($extensionFieldNames, array_keys($fieldInterfaceResolvers)) as $fieldName) {
                        $fieldInterfaceResolvers[$fieldName] ??= [];
                        $fieldInterfaceResolvers[$fieldName][] = $fieldInterfaceResolver;
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

        return $fieldInterfaceResolvers;
    }
}
