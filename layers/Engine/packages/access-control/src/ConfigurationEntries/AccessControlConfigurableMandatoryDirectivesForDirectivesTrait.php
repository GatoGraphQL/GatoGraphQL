<?php

declare(strict_types=1);

namespace PoP\AccessControl\ConfigurationEntries;

use PoP\AccessControl\ComponentConfiguration;
use PoP\MandatoryDirectivesByConfiguration\ConfigurationEntries\ConfigurableMandatoryDirectivesForDirectivesTrait;

trait AccessControlConfigurableMandatoryDirectivesForDirectivesTrait
{
    use ConfigurableMandatoryDirectivesForDirectivesTrait {
        ConfigurableMandatoryDirectivesForDirectivesTrait::getMatchingEntries as getUpstreamMatchingEntries;
    }
    use AccessControlConfigurableMandatoryDirectivesForItemsTrait;

    /**
     * Filter all the entries from the list which apply to the passed typeResolver and fieldName
     */
    final protected function getMatchingEntries(array $entryList, ?string $value): array
    {
        /**
         * If enabling individual control over public/private schema modes, then we must also check
         * that this field has the required mode.
         * If the schema mode was not defined in the entry, then this field is valid if the default
         * schema mode is the same required one
         */
        if (!ComponentConfiguration::enableIndividualControlForPublicPrivateSchemaMode()) {
            return $this->getUpstreamMatchingEntries($entryList, $value);
        }
        /**
         * Sometimes there's a value (eg: filter by role), sometimes not (eg: disable).
         * When there's a value, filter all entries that contain it
         */
        if ($value) {
            $entryList = array_filter(
                $entryList,
                fn ($entry) => ($entry[1] ?? null) == $value
            );
        }
        /**
         * Then filter all entries by individual access control
         */
        $individualControlSchemaMode = $this->getSchemaMode();
        $matchNullControlEntry = $this->doesSchemaModeProcessNullControlEntry();
        return array_filter(
            $entryList,
            fn ($entry) =>
                (
                    isset($entry[2])
                    && $entry[2] == $individualControlSchemaMode
                )
                || (
                    !isset($entry[2]) &&
                    $matchNullControlEntry
                )
        );
    }
}
