<?php

declare(strict_types=1);

namespace PoP\MandatoryDirectivesByConfiguration\ConfigurationEntries;

use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\ObjectTypeFieldResolverInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

trait ConfigurableMandatoryDirectivesForFieldsTrait
{
    /**
     * Configuration entries
     */
    abstract protected function getConfigurationEntries(): array;

    /**
     * Field names to remove
     */
    protected function getFieldNames(): array
    {
        return array_map(
            // The tuple has format [typeOrInterfaceTypeFieldResolverClass, fieldName]
            // or [typeOrInterfaceTypeFieldResolverClass, fieldName, $role]
            // or [typeOrInterfaceTypeFieldResolverClass, fieldName, $capability]
            // So, in position [1], will always be the $fieldName
            fn (array $entry) => $entry[1],
            $this->getConfigurationEntries()
        );
    }

    /**
     * Configuration entries
     */
    final protected function getEntries(
        ObjectTypeResolverInterface | InterfaceTypeResolverInterface $objectTypeOrInterfaceTypeResolver,
        ObjectTypeFieldResolverInterface | InterfaceTypeFieldResolverInterface $objectTypeOrInterfaceTypeFieldResolver,
        string $fieldName
    ): array {
        return $this->getEntriesByTypeAndInterfaces(
            $objectTypeOrInterfaceTypeResolver,
            /**
             * Pass the list of all the interfaces implemented by the objectTypeOrInterfaceTypeFieldResolver,
             * and not only those ones containing the fieldName.
             * This is because otherwise we'd need to call `$interfaceTypeResolver->getFieldNamesToImplement()`
             * to find out the list of Interfaces containing $fieldName, however this function relies
             * on the InterfaceTypeFieldResolver once again, so we'd get a recursion.
             */
            $objectTypeOrInterfaceTypeFieldResolver->getPartiallyImplementedInterfaceTypeResolvers(),
            $fieldName
        );
    }

    /**
     * Configuration entries
     *
     * @param InterfaceTypeResolverInterface[] $interfaceTypeResolvers
     */
    final protected function getEntriesByTypeAndInterfaces(
        ObjectTypeResolverInterface | InterfaceTypeResolverInterface $objectTypeOrInterfaceTypeResolver,
        array $interfaceTypeResolvers,
        string $fieldName
    ): array {
        return $this->getMatchingEntries(
            $this->getConfigurationEntries(),
            $objectTypeOrInterfaceTypeResolver,
            $interfaceTypeResolvers,
            $fieldName
        );
    }

    /**
     * Filter all the entries from the list which apply to the passed typeResolver and fieldName
     *
     * @param InterfaceTypeResolverInterface[] $interfaceTypeResolvers
     */
    final protected function getMatchingEntries(
        array $entryList,
        ObjectTypeResolverInterface | InterfaceTypeResolverInterface $objectTypeOrInterfaceTypeResolver,
        array $interfaceTypeResolvers,
        string $fieldName
    ): array {
        $objectTypeOrInterfaceTypeResolverClass = get_class($objectTypeOrInterfaceTypeResolver);
        $interfaceTypeResolverClasses = array_map(
            get_class(...),
            $interfaceTypeResolvers
        );
        return array_values(array_filter(
            $entryList,
            fn (array $entry) => (
                $entry[0] === $objectTypeOrInterfaceTypeResolverClass
                || in_array($entry[0], $interfaceTypeResolverClasses)
            ) && $entry[1] === $fieldName
        ));
    }
}
