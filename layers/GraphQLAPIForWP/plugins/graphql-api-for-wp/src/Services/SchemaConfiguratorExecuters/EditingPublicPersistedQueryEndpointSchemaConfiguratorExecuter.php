<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfiguratorExecuters;

use GraphQLAPI\GraphQLAPI\Services\Helpers\EndpointHelpers;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\PublicPersistedQueryEndpointSchemaConfigurator;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\SchemaConfiguratorInterface;

class EditingPublicPublicPersistedQueryEndpointSchemaConfiguratorExecuter extends AbstractSchemaConfiguratorExecuter
{
    private ?EndpointHelpers $endpointHelpers = null;
    private ?PublicPersistedQueryEndpointSchemaConfigurator $publicPersistedQueryEndpointSchemaConfigurator = null;

    final public function setEndpointHelpers(EndpointHelpers $endpointHelpers): void
    {
        $this->endpointHelpers = $endpointHelpers;
    }
    final protected function getEndpointHelpers(): EndpointHelpers
    {
        /** @var EndpointHelpers */
        return $this->endpointHelpers ??= $this->instanceManager->getInstance(EndpointHelpers::class);
    }
    final public function setPublicPersistedQueryEndpointSchemaConfigurator(PublicPersistedQueryEndpointSchemaConfigurator $publicPersistedQueryEndpointSchemaConfigurator): void
    {
        $this->publicPersistedQueryEndpointSchemaConfigurator = $publicPersistedQueryEndpointSchemaConfigurator;
    }
    final protected function getPublicPersistedQueryEndpointSchemaConfigurator(): PublicPersistedQueryEndpointSchemaConfigurator
    {
        /** @var PublicPersistedQueryEndpointSchemaConfigurator */
        return $this->publicPersistedQueryEndpointSchemaConfigurator ??= $this->instanceManager->getInstance(PublicPersistedQueryEndpointSchemaConfigurator::class);
    }

    /**
     * Initialize the configuration if editing a persisted query
     */
    protected function getCustomPostID(): ?int
    {
        if ($this->getEndpointHelpers()->isRequestingAdminPersistedQueryGraphQLEndpoint()) {
            return (int) $this->getEndpointHelpers()->getAdminPersistedQueryCustomPostID();
        }
        return null;
    }

    protected function getSchemaConfigurator(): SchemaConfiguratorInterface
    {
        return $this->getPublicPersistedQueryEndpointSchemaConfigurator();
    }
}
