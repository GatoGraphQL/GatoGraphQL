<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters;

use GraphQLAPI\GraphQLAPI\App;
use GraphQLAPI\GraphQLAPI\Constants\BlockAttributeNames;
use GraphQLAPI\GraphQLAPI\StaticHelpers\BehaviorHelpers;
use PoPSchema\SchemaCommons\Constants\Behaviors;

abstract class AbstractSchemaAllowAccessToEntriesSchemaConfigurationExecuter extends AbstractCustomizableConfigurationSchemaConfigurationExecuter implements PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface, EndpointSchemaConfigurationExecuterServiceTagInterface
{
    /**
     * @param array<string,mixed> $blockDataItem
     */
    protected function doExecuteSchemaConfiguration(array $blockDataItem): void
    {
        $entries = $blockDataItem['attrs'][BlockAttributeNames::ENTRIES] ?? [];
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
        $behavior = $blockDataItem['attrs'][BlockAttributeNames::BEHAVIOR] ?? $this->getDefaultBehavior();
        /**
         * Define the settings value through a hook.
         * Execute last so it overrides the default settings
         */
        $hookName = $this->getBehaviorHookName();
        App::addFilter(
            $hookName,
            fn () => $behavior,
            PHP_INT_MAX
        );
    }

    abstract protected function getEntriesHookName(): string;
    abstract protected function getBehaviorHookName(): string;

    protected function getDefaultBehavior(): string
    {
        $useRestrictiveDefaults = BehaviorHelpers::areRestrictiveDefaultsEnabled();
        return $useRestrictiveDefaults ? Behaviors::ALLOW : Behaviors::DENY;
    }
}
