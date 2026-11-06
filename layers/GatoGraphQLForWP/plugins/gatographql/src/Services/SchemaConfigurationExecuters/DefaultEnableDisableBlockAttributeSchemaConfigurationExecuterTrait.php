<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\SchemaConfigurationExecuters;

use GatoGraphQL\GatoGraphQL\App;
use GatoGraphQL\GatoGraphQL\Constants\BlockAttributeValues;
use PoP\Root\Module\ModuleConfigurationHelpers;

use function in_array;

trait DefaultEnableDisableBlockAttributeSchemaConfigurationExecuterTrait
{
    /**
     * Override the module configuration value with the one
     * selected in the block attribute, if it is "enabled"
     * or "disabled". If "default", then the general
     * settings will already take effect, so do nothing.
     *
     * @param array<string,mixed> $schemaConfigBlockDataItem
     */
    protected function executeDefaultEnableDisableBlockAttribute(
        array $schemaConfigBlockDataItem,
        string $attributeName,
        string $hookModuleClass,
        string $hookEnvironmentClass,
    ): void {
        $enableFunctionality = $schemaConfigBlockDataItem['attrs'][$attributeName] ?? null;
        if (
            !in_array($enableFunctionality, [
                BlockAttributeValues::ENABLED,
                BlockAttributeValues::DISABLED,
            ])
        ) {
            return;
        }
        /**
         * Define the settings value through a hook.
         * Execute last so it overrides the default settings
         */
        $hookName = ModuleConfigurationHelpers::getHookName(
            $hookModuleClass,
            $hookEnvironmentClass,
        );
        App::addFilter(
            $hookName,
            fn () => $enableFunctionality === BlockAttributeValues::ENABLED,
            PHP_INT_MAX
        );
    }
}
