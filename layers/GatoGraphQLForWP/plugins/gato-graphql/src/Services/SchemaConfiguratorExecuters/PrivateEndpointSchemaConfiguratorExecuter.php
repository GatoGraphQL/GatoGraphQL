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

    final public function setPrivateEndpointSchemaConfigurator(PrivateEndpointSchemaConfigurator $privateEndpointSchemaConfigurator): void
    {
        $this->privateEndpointSchemaConfigurator = $privateEndpointSchemaConfigurator;
    }
    final protected function getPrivateEndpointSchemaConfigurator(): PrivateEndpointSchemaConfigurator
    {
        /** @var PrivateEndpointSchemaConfigurator */
        return $this->privateEndpointSchemaConfigurator ??= $this->instanceManager->getInstance(PrivateEndpointSchemaConfigurator::class);
    }
    final public function setEndpointBlockHelpers(EndpointBlockHelpers $endpointBlockHelpers): void
    {
        $this->endpointBlockHelpers = $endpointBlockHelpers;
    }
    final protected function getEndpointBlockHelpers(): EndpointBlockHelpers
    {
        /** @var EndpointBlockHelpers */
        return $this->endpointBlockHelpers ??= $this->instanceManager->getInstance(EndpointBlockHelpers::class);
    }
    final public function setEndpointHelpers(EndpointHelpers $endpointHelpers): void
    {
        $this->endpointHelpers = $endpointHelpers;
    }
    final protected function getEndpointHelpers(): EndpointHelpers
    {
        /** @var EndpointHelpers */
        return $this->endpointHelpers ??= $this->instanceManager->getInstance(EndpointHelpers::class);
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
