<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters;

use GraphQLAPI\GraphQLAPI\PluginEnvironment;
use GraphQLAPI\GraphQLAPI\Services\Blocks\AbstractSchemaConfigSchemaAllowAccessToEntriesBlock;
use PoPSchema\SchemaCommons\Constants\Behaviors;

abstract class AbstractSchemaAllowAccessToEntriesSchemaConfigurationExecuter extends AbstractCustomizableConfigurationSchemaConfigurationExecuter implements PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface, EndpointSchemaConfigurationExecuterServiceTagInterface
{
    protected function doExecuteSchemaConfiguration(int $schemaConfigurationID): void
    {
        $schemaConfigBlockDataItem = $this->getSchemaConfigBlockDataItem($schemaConfigurationID);
        if ($schemaConfigBlockDataItem === null) {
            return;
        }
        $entries = $schemaConfigBlockDataItem['attrs'][AbstractSchemaConfigSchemaAllowAccessToEntriesBlock::ATTRIBUTE_NAME_ENTRIES] ?? [];
        /**
         * Define the settings value through a hook.
         * Execute last so it overrides the default settings
         */
        $hookName = $this->getEntriesHookName();
        \add_filter(
            $hookName,
            fn () => $entries,
            PHP_INT_MAX
        );
        $behavior = $schemaConfigBlockDataItem['attrs'][AbstractSchemaConfigSchemaAllowAccessToEntriesBlock::ATTRIBUTE_NAME_BEHAVIOR] ?? $this->getDefaultBehavior();
        /**
         * Define the settings value through a hook.
         * Execute last so it overrides the default settings
         */
        $hookName = $this->getBehaviorHookName();
        \add_filter(
            $hookName,
            fn () => $behavior,
            PHP_INT_MAX
        );
    }

    abstract protected function getEntriesHookName(): string;
    abstract protected function getBehaviorHookName(): string;

    protected function getDefaultBehavior(): string
    {
        return PluginEnvironment::areUnsafeDefaultsEnabled()
            ? Behaviors::DENY
            : Behaviors::ALLOW;
    }
}
