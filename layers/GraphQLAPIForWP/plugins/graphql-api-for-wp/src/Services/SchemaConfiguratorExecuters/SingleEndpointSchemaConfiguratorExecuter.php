<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfiguratorExecuters;

use GraphQLAPI\GraphQLAPI\Facades\UserSettingsManagerFacade;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\SchemaConfiguratorInterface;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\SingleEndpointSchemaConfigurator;
use PoP\ComponentModel\Instances\InstanceManagerInterface;

class SingleEndpointSchemaConfiguratorExecuter extends AbstractSchemaConfiguratorExecuter
{
    public function __construct(
        InstanceManagerInterface $instanceManager,
        protected ModuleRegistryInterface $moduleRegistry,
        protected SingleEndpointSchemaConfigurator $endpointSchemaConfigurator,
    ) {
        parent::__construct(
            $instanceManager,
        );
    }

    /**
     * This is the Schema Configuration ID
     */
    protected function getCustomPostID(): ?int
    {
        // Only enable it when executing a query against the single endpoint
        if (true) {
            return null;
        }
        return $this->getUserSettingSchemaConfigurationID();
    }
    
    /**
     * Return the stored Schema Configuration ID
     */
    protected function getUserSettingSchemaConfigurationID(): ?int
    {
        $userSettingsManager = UserSettingsManagerFacade::getInstance();
        $schemaConfigurationID = $userSettingsManager->getSetting(
            SchemaConfigurationFunctionalityModuleResolver::SCHEMA_CONFIGURATION,
            SchemaConfigurationFunctionalityModuleResolver::OPTION_SCHEMA_CONFIGURATION_ID
        );
        // `null` is stored as OPTION_VALUE_NO_VALUE_ID
        if ($schemaConfigurationID == SchemaConfigurationFunctionalityModuleResolver::OPTION_VALUE_NO_VALUE_ID) {
            return null;
        }
        return $schemaConfigurationID;
    }

    protected function getSchemaConfigurator(): SchemaConfiguratorInterface
    {
        return $this->endpointSchemaConfigurator;
    }
}
