<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\Helpers;

use GatoGraphQL\GatoGraphQL\Constants\ModuleSettingOptionValues;
use GatoGraphQL\GatoGraphQL\Constants\ModuleSettingOptions;
use GatoGraphQL\GatoGraphQL\Facades\UserSettingsManagerFacade;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\EndpointConfigurationFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\Registries\ModuleRegistryInterface;
use GatoGraphQL\GatoGraphQL\Services\Blocks\EndpointSchemaConfigurationBlock;
use GatoGraphQL\GatoGraphQL\Settings\UserSettingsManagerInterface;
use PoP\Root\Services\BasicServiceTrait;
use WP_Post;

class EndpointBlockHelpers
{
    use BasicServiceTrait;

    private ?UserSettingsManagerInterface $userSettingsManager = null;
    private ?ModuleRegistryInterface $moduleRegistry = null;
    private ?BlockHelpers $blockHelpers = null;
    private ?EndpointSchemaConfigurationBlock $endpointSchemaConfigurationBlock = null;

    protected function getUserSettingsManager(): UserSettingsManagerInterface
    {
        return $this->userSettingsManager ??= UserSettingsManagerFacade::getInstance();
    }
    final protected function getModuleRegistry(): ModuleRegistryInterface
    {
        if ($this->moduleRegistry === null) {
            /** @var ModuleRegistryInterface */
            $moduleRegistry = $this->instanceManager->getInstance(ModuleRegistryInterface::class);
            $this->moduleRegistry = $moduleRegistry;
        }
        return $this->moduleRegistry;
    }
    final protected function getBlockHelpers(): BlockHelpers
    {
        if ($this->blockHelpers === null) {
            /** @var BlockHelpers */
            $blockHelpers = $this->instanceManager->getInstance(BlockHelpers::class);
            $this->blockHelpers = $blockHelpers;
        }
        return $this->blockHelpers;
    }
    final protected function getEndpointSchemaConfigurationBlock(): EndpointSchemaConfigurationBlock
    {
        if ($this->endpointSchemaConfigurationBlock === null) {
            /** @var EndpointSchemaConfigurationBlock */
            $endpointSchemaConfigurationBlock = $this->instanceManager->getInstance(EndpointSchemaConfigurationBlock::class);
            $this->endpointSchemaConfigurationBlock = $endpointSchemaConfigurationBlock;
        }
        return $this->endpointSchemaConfigurationBlock;
    }

    /**
     * Extract the Schema Configuration ID from the block stored in the post.
     *
     * @return int|null The Schema Configuration ID, null if none was selected (in which case a default Schema Configuration can be applied), or -1 if "None" was selected (i.e. no default Schema Configuration must be applied)
     */
    public function getSchemaConfigurationID(string $module, int $customPostID): ?int
    {
        $schemaConfigurationBlockDataItem = $this->getBlockHelpers()->getSingleBlockOfTypeFromCustomPost(
            $customPostID,
            $this->getEndpointSchemaConfigurationBlock()
        );

        /**
         * If there was no schema configuration, then the default one
         * has been selected.
         * It is not saved in the DB, because it has been set as the
         * default value in blocks/schema-configuration/src/index.js
         */
        if ($schemaConfigurationBlockDataItem === null) {
            return $this->getUserSettingSchemaConfigurationID($module);
        }

        $schemaConfiguration = $schemaConfigurationBlockDataItem['attrs'][EndpointSchemaConfigurationBlock::ATTRIBUTE_NAME_SCHEMA_CONFIGURATION] ?? EndpointSchemaConfigurationBlock::ATTRIBUTE_VALUE_SCHEMA_CONFIGURATION_DEFAULT;

        // If the schema config was not stored, it's the default attribute
        if ($schemaConfiguration === EndpointSchemaConfigurationBlock::ATTRIBUTE_VALUE_SCHEMA_CONFIGURATION_DEFAULT) {
            return $this->getUserSettingSchemaConfigurationID($module);
        }

        // Return `-1` to allow to signify "Do not apply a Schema Configuration at all"
        if ($schemaConfiguration === EndpointSchemaConfigurationBlock::ATTRIBUTE_VALUE_SCHEMA_CONFIGURATION_NONE) {
            return $schemaConfiguration;
        }

        if ($schemaConfiguration === EndpointSchemaConfigurationBlock::ATTRIBUTE_VALUE_SCHEMA_CONFIGURATION_INHERIT) {
            // If disabled by module, then return nothing
            if (!$this->getModuleRegistry()->isModuleEnabled(EndpointConfigurationFunctionalityModuleResolver::API_HIERARCHY)) {
                return null;
            }

            /**
             * Return the schema configuration from the parent,
             * or null if no parent exists
             *
             * @var WP_Post|null
             */
            $customPost = \get_post($customPostID);
            if ($customPost !== null && $customPost->post_parent) {
                return $this->getSchemaConfigurationID($module, $customPost->post_parent);
            }
            return null;
        }

        /**
         * It is already the ID, or null if blocks returned empty
         * (eg: because parent post was trashed)
         */
        return $schemaConfiguration;
    }

    /**
     * Return the stored Schema Configuration ID
     */
    public function getUserSettingSchemaConfigurationID(string $module): ?int
    {
        $schemaConfigurationID = $this->getUserSettingsManager()->getSetting(
            $module,
            ModuleSettingOptions::SCHEMA_CONFIGURATION
        );
        // `null` is stored as OPTION_VALUE_NO_VALUE_ID
        if ($schemaConfigurationID === ModuleSettingOptionValues::NO_VALUE_ID) {
            return null;
        }
        return $schemaConfigurationID;
    }
}
