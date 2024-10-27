<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\SchemaConfiguratorExecuters;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\Services\Helpers\EndpointBlockHelpers;
use GatoGraphQL\GatoGraphQL\Services\Helpers\EndpointHelpers;
use GatoGraphQL\GatoGraphQL\Services\SchemaConfigurators\PrivateEndpointSchemaConfigurator;
use GatoGraphQL\GatoGraphQL\Services\SchemaConfigurators\SchemaConfiguratorInterface;

class PrivateEndpointSchemaConfiguratorExecuter extends AbstractSchemaConfiguratorExecuter
{
    private ?PrivateEndpointSchemaConfigurator $privateEndpointSchemaConfigurator = null;
    private ?EndpointBlockHelpers $endpointBlockHelpers = null;
    private ?EndpointHelpers $endpointHelpers = null;

    final protected function getPrivateEndpointSchemaConfigurator(): PrivateEndpointSchemaConfigurator
    {
        if ($this->privateEndpointSchemaConfigurator === null) {
            /** @var PrivateEndpointSchemaConfigurator */
            $privateEndpointSchemaConfigurator = $this->instanceManager->getInstance(PrivateEndpointSchemaConfigurator::class);
            $this->privateEndpointSchemaConfigurator = $privateEndpointSchemaConfigurator;
        }
        return $this->privateEndpointSchemaConfigurator;
    }
    final protected function getEndpointBlockHelpers(): EndpointBlockHelpers
    {
        if ($this->endpointBlockHelpers === null) {
            /** @var EndpointBlockHelpers */
            $endpointBlockHelpers = $this->instanceManager->getInstance(EndpointBlockHelpers::class);
            $this->endpointBlockHelpers = $endpointBlockHelpers;
        }
        return $this->endpointBlockHelpers;
    }
    final protected function getEndpointHelpers(): EndpointHelpers
    {
        if ($this->endpointHelpers === null) {
            /** @var EndpointHelpers */
            $endpointHelpers = $this->instanceManager->getInstance(EndpointHelpers::class);
            $this->endpointHelpers = $endpointHelpers;
        }
        return $this->endpointHelpers;
    }

    /**
     * Only enable it when executing a query against the private endpoint
     */
    protected function isSchemaConfiguratorActive(): bool
    {
        return $this->getEndpointHelpers()->isRequestingDefaultAdminGraphQLEndpoint();
    }

    /**
     * This is the Schema Configuration ID.
     *
     * @return int|null The Schema Configuration ID, null if none was selected (in which case a default Schema Configuration can be applied), or -1 if "None" was selected (i.e. no default Schema Configuration must be applied)
     */
    protected function getSchemaConfigurationID(): ?int
    {
        // Return the stored Schema Configuration ID
        return $this->getEndpointBlockHelpers()->getUserSettingSchemaConfigurationID(EndpointFunctionalityModuleResolver::PRIVATE_ENDPOINT);
    }

    protected function getSchemaConfigurator(): SchemaConfiguratorInterface
    {
        return $this->getPrivateEndpointSchemaConfigurator();
    }
}
