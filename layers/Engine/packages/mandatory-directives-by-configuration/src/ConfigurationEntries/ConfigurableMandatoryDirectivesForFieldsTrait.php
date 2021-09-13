<?php

declare(strict_types=1);

namespace PoP\MandatoryDirectivesByConfiguration\ConfigurationEntries;

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
        array $interfaceTypeResolverClasses,
        string $fieldName
    ): array {
        return $this->getMatchingEntries(
            $this->getConfigurationEntries(),
            $objectTypeOrInterfaceTypeResolver,
            $interfaceTypeResolverClasses,
            $fieldName
        );
    }

    /**
     * Filter all the entries from the list which apply to the passed typeResolver and fieldName
     */
    final protected function getMatchingEntries(
        array $entryList,
        ObjectTypeResolverInterface | InterfaceTypeResolverInterface $objectTypeOrInterfaceTypeResolver,
        array $interfaceTypeResolverClasses,
        string $fieldName
    ): array {
        $objectTypeOrInterfaceTypeResolverClass = get_class($objectTypeOrInterfaceTypeResolver);
        return array_filter(
            $entryList,
            fn (array $entry) => (
                $entry[0] === $objectTypeOrInterfaceTypeResolverClass
                || in_array($entry[0], $interfaceTypeResolverClasses)
            ) && $entry[1] == $fieldName
        );
    }
}
