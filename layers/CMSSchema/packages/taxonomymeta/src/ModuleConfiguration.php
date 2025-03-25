<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMeta;

use PoP\Root\Module\AbstractModuleConfiguration;
use PoP\Root\Module\EnvironmentValueHelpers;
use PoPSchema\SchemaCommons\Constants\Behaviors;

class ModuleConfiguration extends AbstractModuleConfiguration
{
    /**
     * @return string[]
     */
    public function getTaxonomyMetaEntries(): array
    {
        $envVariable = Environment::TAXONOMY_META_ENTRIES;
        $defaultValue = [];
        $callback = EnvironmentValueHelpers::commaSeparatedStringToArray(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function getTaxonomyMetaBehavior(): string
    {
        $envVariable = Environment::TAXONOMY_META_BEHAVIOR;
        $defaultValue = Behaviors::ALLOW;

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
        );
    }

    public function treatTaxonomyMetaKeysAsSensitiveData(): bool
    {
        $envVariable = Environment::TREAT_TAXONOMY_META_KEYS_AS_SENSITIVE_DATA;
        $defaultValue = true;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }
}
