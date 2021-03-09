<?php

declare(strict_types=1);

namespace PoP\MandatoryDirectivesByConfiguration\ConfigurationEntries;

use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;

trait ConfigurableMandatoryDirectivesForFieldsTrait
{
    /**
     * Configuration entries
     *
     * @return array
     */
    abstract protected function getConfigurationEntries(): array;

    /**
     * Field names to remove
     *
     * @return array
     */
    protected function getFieldNames(): array
    {
        return array_map(
            function ($entry) {
                // The tuple has format [typeOrFieldInterfaceResolverClass, fieldName]
                // or [typeOrFieldInterfaceResolverClass, fieldName, $role]
                // or [typeOrFieldInterfaceResolverClass, fieldName, $capability]
                // So, in position [1], will always be the $fieldName
                return $entry[1];
            },
            $this->getConfigurationEntries()
        );
    }

    /**
     * Configuration entries
     *
     * @return array
     */
    final protected function getEntries(
        TypeResolverInterface $typeResolver,
        array $fieldInterfaceResolverClasses,
        string $fieldName
    ): array {
        return $this->getMatchingEntries(
            $this->getConfigurationEntries(),
            $typeResolver,
            $fieldInterfaceResolverClasses,
            $fieldName
        );
    }

    /**
     * Filter all the entries from the list which apply to the passed typeResolver and fieldName
     *
     * @param boolean $include
     * @param array $entryList
     * @param TypeResolverInterface $typeResolver
     * @param string $fieldName
     * @return boolean
     */
    final protected function getMatchingEntries(
        array $entryList,
        TypeResolverInterface $typeResolver,
        array $fieldInterfaceResolverClasses,
        string $fieldName
    ): array {
        $typeResolverClass = get_class($typeResolver);
        return array_filter(
            $entryList,
            function ($entry) use ($typeResolverClass, $fieldInterfaceResolverClasses, $fieldName): bool {
                return (
                    $entry[0] == $typeResolverClass
                    || in_array($entry[0], $fieldInterfaceResolverClasses)
                ) && $entry[1] == $fieldName;
            }
        );
    }
}
