<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfiguratorExecuters;

use GraphQLAPI\GraphQLAPI\Services\Helpers\EndpointHelpers;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\PrivatePersistedQueryEndpointSchemaConfigurator;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\SchemaConfiguratorInterface;

class EditingPrivatePersistedQueryEndpointSchemaConfiguratorExecuter extends AbstractSchemaConfiguratorExecuter
{
    private ?EndpointHelpers $endpointHelpers = null;
    private ?PrivatePersistedQueryEndpointSchemaConfigurator $privatePersistedQueryEndpointSchemaConfigurator = null;

    final public function setEndpointHelpers(EndpointHelpers $endpointHelpers): void
    {
        $this->endpointHelpers = $endpointHelpers;
    }
    final protected function getEndpointHelpers(): EndpointHelpers
    {
        /** @var EndpointHelpers */
        return $this->endpointHelpers ??= $this->instanceManager->getInstance(EndpointHelpers::class);
    }
    final public function setPrivatePersistedQueryEndpointSchemaConfigurator(PrivatePersistedQueryEndpointSchemaConfigurator $privatePersistedQueryEndpointSchemaConfigurator): void
    {
        $this->privatePersistedQueryEndpointSchemaConfigurator = $privatePersistedQueryEndpointSchemaConfigurator;
    }
    final protected function getPrivatePersistedQueryEndpointSchemaConfigurator(): PrivatePersistedQueryEndpointSchemaConfigurator
    {
        /** @var PrivatePersistedQueryEndpointSchemaConfigurator */
        return $this->privatePersistedQueryEndpointSchemaConfigurator ??= $this->instanceManager->getInstance(PrivatePersistedQueryEndpointSchemaConfigurator::class);
    }

    /**
     * Initialize the configuration if editing a persisted query
     */
    protected function getCustomPostID(): ?int
    {
        if ($this->getEndpointHelpers()->isRequestingAdminPrivatePersistedQueryGraphQLEndpoint()) {
            return (int) $this->getEndpointHelpers()->getAdminPersistedQueryCustomPostID();
        }
        return null;
    }

    protected function getSchemaConfigurator(): SchemaConfiguratorInterface
    {
        return $this->getPrivatePersistedQueryEndpointSchemaConfigurator();
    }
}
