<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMeta;

use PoP\Root\Module\AbstractModuleConfiguration;
use PoP\Root\Module\EnvironmentValueHelpers;
use PoPSchema\SchemaCommons\Enums\Behaviors;

class ModuleConfiguration extends AbstractModuleConfiguration
{
    /**
     * @return string[]
     */
    public function getUserMetaEntries(): array
    {
        $envVariable = Environment::USER_META_ENTRIES;
        $defaultValue = [];
        $callback = EnvironmentValueHelpers::commaSeparatedStringToArray(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function getUserMetaBehavior(): Behaviors
    {
        $envVariable = Environment::USER_META_BEHAVIOR;
        $defaultValue = Behaviors::ALLOW;

        return Behaviors::tryFrom($this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue->value,
        )) ?? $defaultValue;
    }

    public function treatUserMetaKeysAsSensitiveData(): bool
    {
        $envVariable = Environment::TREAT_USER_META_KEYS_AS_SENSITIVE_DATA;
        $defaultValue = true;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }
}
