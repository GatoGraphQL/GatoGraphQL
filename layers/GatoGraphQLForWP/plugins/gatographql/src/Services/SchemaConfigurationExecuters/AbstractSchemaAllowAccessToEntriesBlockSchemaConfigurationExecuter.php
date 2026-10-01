<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\SchemaConfigurationExecuters;

use GatoGraphQL\GatoGraphQL\App;
use GatoGraphQL\GatoGraphQL\Constants\BlockAttributeNames;
use GatoGraphQL\GatoGraphQL\StaticHelpers\BehaviorHelpers;
use PoPSchema\SchemaCommons\Enums\Behaviors;

abstract class AbstractSchemaAllowAccessToEntriesBlockSchemaConfigurationExecuter extends AbstractCustomizableConfigurationBlockSchemaConfigurationExecuter implements PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface, EndpointSchemaConfigurationExecuterServiceTagInterface
{
    /**
     * @param array<string,mixed> $schemaConfigBlockDataItem
     */
    protected function doExecuteBlockSchemaConfiguration(array $schemaConfigBlockDataItem): void
    {
        $entries = $schemaConfigBlockDataItem['attrs'][BlockAttributeNames::ENTRIES] ?? [];
        /**
         * Define the settings value through a hook.
         * Execute last so it overrides the default settings
         */
        $hookName = $this->getEntriesHookName();
        App::addFilter(
            $hookName,
            fn () => $entries,
            PHP_INT_MAX
        );
        $behavior = Behaviors::tryFrom($schemaConfigBlockDataItem['attrs'][BlockAttributeNames::BEHAVIOR] ?? '') ?? $this->getDefaultBehavior();
        /**
         * Define the settings value through a hook.
         * Execute last so it overrides the default settings
         */
        $hookName = $this->getBehaviorHookName();
        App::addFilter(
            $hookName,
            fn () => $behavior->value,
            PHP_INT_MAX
        );
    }

    abstract protected function getEntriesHookName(): string;
    abstract protected function getBehaviorHookName(): string;

    protected function getDefaultBehavior(): Behaviors
    {
        $useRestrictiveDefaults = BehaviorHelpers::areRestrictiveDefaultsEnabled();
        return $useRestrictiveDefaults ? Behaviors::ALLOW : Behaviors::DENY;
    }
}
