<?php

declare(strict_types=1);

namespace PoP\AccessControl\ConfigurationEntries;

use PoP\AccessControl\ComponentConfiguration;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\FieldResolverInterface;
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
        /**
         * If enabling individual control over public/private schema modes, then we must also check
         * that this field has the required mode.
         * If the schema mode was not defined in the entry, then this field is valid if the default
         * schema mode is the same required one
         */
        if (!ComponentConfiguration::enableIndividualControlForPublicPrivateSchemaMode()) {
            return $this->getUpstreamMatchingEntries(
                $entryList,
                $typeResolver,
                $fieldInterfaceResolverClasses,
                $fieldName
            );
        }
        $typeResolverClass = get_class($typeResolver);
        $individualControlSchemaMode = $this->getSchemaMode();
        $matchNullControlEntry = $this->doesSchemaModeProcessNullControlEntry();
        return array_filter(
            $entryList,
            fn ($entry): bool =>
                (
                    $entry[0] == $typeResolverClass
                    || in_array($entry[0], $fieldInterfaceResolverClasses)
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

    public function maybeFilterFieldName(
        bool $include,
        TypeResolverInterface $typeResolver,
        FieldResolverInterface $fieldResolver,
        array $fieldInterfaceResolverClasses,
        string $fieldName
    ): bool {
        /**
         * If enabling individual control, then check if there is any entry for this field and schema mode
         */
        if (ComponentConfiguration::enableIndividualControlForPublicPrivateSchemaMode()) {
            /**
             * If there are no entries, then exit by returning the original hook value
             */
            if (empty($this->getEntries($typeResolver, $fieldInterfaceResolverClasses, $fieldName))) {
                return $include;
            }
        }

        /**
         * The parent case deals with the general case
         */
        return parent::maybeFilterFieldName(
            $include,
            $typeResolver,
            $fieldResolver,
            $fieldInterfaceResolverClasses,
            $fieldName
        );
    }
}
