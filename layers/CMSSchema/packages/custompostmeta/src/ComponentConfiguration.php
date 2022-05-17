<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMeta;

use PoP\Root\Module\AbstractModuleConfiguration;
use PoP\Root\Module\EnvironmentValueHelpers;
use PoPSchema\SchemaCommons\Constants\Behaviors;

class ComponentConfiguration extends AbstractModuleConfiguration
{
    /**
     * @return string[]
     */
    public function getCustomPostMetaEntries(): array
    {
        $envVariable = Environment::CUSTOMPOST_META_ENTRIES;
        $defaultValue = [];
        $callback = EnvironmentValueHelpers::commaSeparatedStringToArray(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function getCustomPostMetaBehavior(): string
    {
        $envVariable = Environment::CUSTOMPOST_META_BEHAVIOR;
        $defaultValue = Behaviors::ALLOWLIST;

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
        );
    }
}
