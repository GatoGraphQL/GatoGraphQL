<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfiguratorExecuters;

use GraphQLAPI\GraphQLAPI\AppHelpers;
use GraphQLAPI\GraphQLAPI\Services\Helpers\EndpointHelpers;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\PersistedQueryEndpointSchemaConfigurator;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\SchemaConfiguratorInterface;

class EditingPersistedQueryEndpointSchemaConfiguratorExecuter extends AbstractSchemaConfiguratorExecuter
{
    private ?EndpointHelpers $endpointHelpers = null;
    private ?PersistedQueryEndpointSchemaConfigurator $persistedQueryEndpointSchemaConfigurator = null;

    final public function setEndpointHelpers(EndpointHelpers $endpointHelpers): void
    {
        $this->endpointHelpers = $endpointHelpers;
    }
    final protected function getEndpointHelpers(): EndpointHelpers
    {
        /** @var EndpointHelpers */
        return $this->endpointHelpers ??= $this->instanceManager->getInstance(EndpointHelpers::class);
    }
    final public function setPersistedQueryEndpointSchemaConfigurator(PersistedQueryEndpointSchemaConfigurator $persistedQueryEndpointSchemaConfigurator): void
    {
        $this->persistedQueryEndpointSchemaConfigurator = $persistedQueryEndpointSchemaConfigurator;
    }
    final protected function getPersistedQueryEndpointSchemaConfigurator(): PersistedQueryEndpointSchemaConfigurator
    {
        /** @var PersistedQueryEndpointSchemaConfigurator */
        return $this->persistedQueryEndpointSchemaConfigurator ??= $this->instanceManager->getInstance(PersistedQueryEndpointSchemaConfigurator::class);
    }
    
    /**
     * Only initialize once, for the main AppThread
     */
    public function isServiceEnabled(): bool
    {
        if (!AppHelpers::isMainAppThread()) {
            return false;
        }
        return parent::isServiceEnabled();
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
        return $this->getPersistedQueryEndpointSchemaConfigurator();
    }
}
