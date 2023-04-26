<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Helpers;

use GraphQLAPI\GraphQLAPI\Constants\ModuleSettingOptionValues;
use GraphQLAPI\GraphQLAPI\Constants\ModuleSettingOptions;
use GraphQLAPI\GraphQLAPI\Facades\UserSettingsManagerFacade;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\EndpointConfigurationFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\Blocks\EndpointSchemaConfigurationBlock;
use GraphQLAPI\GraphQLAPI\Settings\UserSettingsManagerInterface;
use PoP\Root\Services\BasicServiceTrait;
use WP_Post;

class EndpointBlockHelpers
{
    use BasicServiceTrait;

    private ?UserSettingsManagerInterface $userSettingsManager = null;
    private ?ModuleRegistryInterface $moduleRegistry = null;
    private ?BlockHelpers $blockHelpers = null;
    private ?EndpointSchemaConfigurationBlock $endpointSchemaConfigurationBlock = null;

    public function setUserSettingsManager(UserSettingsManagerInterface $userSettingsManager): void
    {
        $this->userSettingsManager = $userSettingsManager;
    }
    protected function getUserSettingsManager(): UserSettingsManagerInterface
    {
        return $this->userSettingsManager ??= UserSettingsManagerFacade::getInstance();
    }
    final public function setModuleRegistry(ModuleRegistryInterface $moduleRegistry): void
    {
        $this->moduleRegistry = $moduleRegistry;
    }
    final protected function getModuleRegistry(): ModuleRegistryInterface
    {
        /** @var ModuleRegistryInterface */
        return $this->moduleRegistry ??= $this->instanceManager->getInstance(ModuleRegistryInterface::class);
    }
    final public function setBlockHelpers(BlockHelpers $blockHelpers): void
    {
        $this->blockHelpers = $blockHelpers;
    }
    final protected function getBlockHelpers(): BlockHelpers
    {
        /** @var BlockHelpers */
        return $this->blockHelpers ??= $this->instanceManager->getInstance(BlockHelpers::class);
    }
    final public function setEndpointSchemaConfigurationBlock(EndpointSchemaConfigurationBlock $endpointSchemaConfigurationBlock): void
    {
        $this->endpointSchemaConfigurationBlock = $endpointSchemaConfigurationBlock;
    }
    final protected function getEndpointSchemaConfigurationBlock(): EndpointSchemaConfigurationBlock
    {
        /** @var EndpointSchemaConfigurationBlock */
        return $this->endpointSchemaConfigurationBlock ??= $this->instanceManager->getInstance(EndpointSchemaConfigurationBlock::class);
    }

    /**
     * Extract the Schema Configuration ID from the block stored in the post
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

        // Check if $schemaConfiguration is one of the meta options (default, none, inherit)
        if ($schemaConfiguration === EndpointSchemaConfigurationBlock::ATTRIBUTE_VALUE_SCHEMA_CONFIGURATION_NONE) {
            return null;
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
