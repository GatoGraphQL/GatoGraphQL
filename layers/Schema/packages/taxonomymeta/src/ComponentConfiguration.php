<?php

declare(strict_types=1);

namespace PoPSchema\TaxonomyMeta;

use PoP\Root\Component\AbstractComponentConfiguration;
use PoP\BasicService\Component\EnvironmentValueHelpers;
use PoPSchema\SchemaCommons\Constants\Behaviors;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    /**
     * @return string[]
     */
    public function getTaxonomyMetaEntries(): array
    {
        $envVariable = Environment::TAXONOMY_META_ENTRIES;
        $defaultValue = [];
        $callback = [EnvironmentValueHelpers::class, 'commaSeparatedStringToArray'];

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function getTaxonomyMetaBehavior(): string
    {
        $envVariable = Environment::TAXONOMY_META_BEHAVIOR;
        $defaultValue = Behaviors::ALLOWLIST;

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
        );
    }
}
