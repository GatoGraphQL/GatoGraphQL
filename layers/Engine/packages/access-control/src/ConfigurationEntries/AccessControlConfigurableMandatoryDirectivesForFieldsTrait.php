<?php

declare(strict_types=1);

namespace PoP\AccessControl\ConfigurationEntries;

use PoP\AccessControl\ComponentConfiguration;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\MandatoryDirectivesByConfiguration\ConfigurationEntries\ConfigurableMandatoryDirectivesForFieldsTrait;

trait AccessControlConfigurableMandatoryDirectivesForFieldsTrait
{
    use ConfigurableMandatoryDirectivesForFieldsTrait {
        ConfigurableMandatoryDirectivesForFieldsTrait::getMatchingEntries as getUpstreamMatchingEntries;
    }
    use AccessControlConfigurableMandatoryDirectivesForItemsTrait;

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
        /**
         * If enabling individual control over public/private schema modes, then we must also check
         * that this field has the required mode.
         * If the schema mode was not defined in the entry, then this field is valid if the default
         * schema mode is the same required one
         */
        if (!ComponentConfiguration::enableIndividualControlForPublicPrivateSchemaMode()) {
            return $this->getUpstreamMatchingEntries(
                $entryList,
                $objectTypeOrInterfaceTypeResolver,
                $interfaceTypeResolvers,
                $fieldName
            );
        }
        $objectTypeOrInterfaceTypeResolverClass = get_class($objectTypeOrInterfaceTypeResolver);
        $interfaceTypeResolverClasses = array_map(
            'get_class',
            $interfaceTypeResolvers
        );
        $individualControlSchemaMode = $this->getSchemaMode();
        $matchNullControlEntry = $this->doesSchemaModeProcessNullControlEntry();
        return array_filter(
            $entryList,
            fn ($entry): bool =>
                (
                    $entry[0] == $objectTypeOrInterfaceTypeResolverClass
                    || in_array($entry[0], $interfaceTypeResolverClasses)
                )
                && $entry[1] == $fieldName
                && (
                    (
                        isset($entry[3])
                        && $entry[3] == $individualControlSchemaMode
                    )
                    || (
                        !isset($entry[3])
                        && $matchNullControlEntry
                    )
                )
        );
    }
}
