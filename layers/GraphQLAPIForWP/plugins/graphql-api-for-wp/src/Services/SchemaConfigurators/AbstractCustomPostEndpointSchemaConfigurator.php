<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators;

use GraphQLAPI\GraphQLAPI\Constants\ModuleSettingOptions;
use GraphQLAPI\GraphQLAPI\Constants\ModuleSettingOptionValues;
use GraphQLAPI\GraphQLAPI\Facades\UserSettingsManagerFacade;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\Blocks\EndpointSchemaConfigurationBlock;
use GraphQLAPI\GraphQLAPI\Settings\UserSettingsManagerInterface;
use Symfony\Contracts\Service\Attribute\Required;
use WP_Post;

abstract class AbstractCustomPostEndpointSchemaConfigurator extends AbstractEndpointSchemaConfigurator
{
    private ?UserSettingsManagerInterface $userSettingsManager = null;
    private ?EndpointSchemaConfigurationBlock $endpointSchemaConfigurationBlock = null;

    public function setUserSettingsManager(UserSettingsManagerInterface $userSettingsManager): void
    {
        $this->userSettingsManager = $userSettingsManager;
    }
    protected function getUserSettingsManager(): UserSettingsManagerInterface
    {
        return $this->userSettingsManager ??= UserSettingsManagerFacade::getInstance();
    }
    public function setEndpointSchemaConfigurationBlock(EndpointSchemaConfigurationBlock $endpointSchemaConfigurationBlock): void
    {
        $this->endpointSchemaConfigurationBlock = $endpointSchemaConfigurationBlock;
    }
    protected function getEndpointSchemaConfigurationBlock(): EndpointSchemaConfigurationBlock
    {
        return $this->endpointSchemaConfigurationBlock ??= $this->instanceManager->getInstance(EndpointSchemaConfigurationBlock::class);
    }

    //#[Required]
    final public function autowireAbstractCustomPostEndpointSchemaConfigurator(
        EndpointSchemaConfigurationBlock $endpointSchemaConfigurationBlock,
    ): void {
        $this->endpointSchemaConfigurationBlock = $endpointSchemaConfigurationBlock;
    }

    /**
     * Extract the Schema Configuration ID from the block stored in the post
     */
    protected function getSchemaConfigurationID(int $customPostID): ?int
    {
        $schemaConfigurationBlockDataItem = $this->getBlockHelpers()->getSingleBlockOfTypeFromCustomPost(
            $customPostID,
            $this->getEndpointSchemaConfigurationBlock()
        );
        // If there was no schema configuration, then the default one has been selected
        // It is not saved in the DB, because it has been set as the default value in
        // blocks/schema-configuration/src/index.js
        if (is_null($schemaConfigurationBlockDataItem)) {
            return $this->getUserSettingSchemaConfigurationID();
        }

        $schemaConfiguration = $schemaConfigurationBlockDataItem['attrs'][EndpointSchemaConfigurationBlock::ATTRIBUTE_NAME_SCHEMA_CONFIGURATION] ?? null;
        // Check if $schemaConfiguration is one of the meta options (default, none, inherit)
        if ($schemaConfiguration == EndpointSchemaConfigurationBlock::ATTRIBUTE_VALUE_SCHEMA_CONFIGURATION_NONE) {
            return null;
        } elseif ($schemaConfiguration == EndpointSchemaConfigurationBlock::ATTRIBUTE_VALUE_SCHEMA_CONFIGURATION_DEFAULT) {
            return $this->getUserSettingSchemaConfigurationID();
        } elseif ($schemaConfiguration == EndpointSchemaConfigurationBlock::ATTRIBUTE_VALUE_SCHEMA_CONFIGURATION_INHERIT) {
            // If disabled by module, then return nothing
            if (!$this->getModuleRegistry()->isModuleEnabled(EndpointFunctionalityModuleResolver::API_HIERARCHY)) {
                return null;
            }
            // Return the schema configuration from the parent, or null if no parent exists
            /**
             * @var WP_Post|null
             */
            $customPost = \get_post($customPostID);
            if (!is_null($customPost) && $customPost->post_parent) {
                return $this->getSchemaConfigurationID($customPost->post_parent);
            }
            return null;
        }
        // It is already the ID, or null if blocks returned empty
        // (eg: because parent post was trashed)
        return $schemaConfiguration;
    }

    /**
     * Return the stored Schema Configuration ID
     */
    protected function getUserSettingSchemaConfigurationID(): ?int
    {
        $schemaConfigurationID = $this->getUserSettingsManager()->getSetting(
            SchemaConfigurationFunctionalityModuleResolver::SCHEMA_CONFIGURATION,
            ModuleSettingOptions::DEFAULT_VALUE
        );
        // `null` is stored as OPTION_VALUE_NO_VALUE_ID
        if ($schemaConfigurationID == ModuleSettingOptionValues::NO_VALUE_ID) {
            return null;
        }
        return $schemaConfigurationID;
    }
}
