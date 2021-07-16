<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfiguratorExecuters;

use GraphQLAPI\GraphQLAPI\Services\Helpers\EndpointHelpers;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\PersistedQueryEndpointSchemaConfigurator;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\SchemaConfiguratorInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;

class EditingPersistedQueryEndpointSchemaConfiguratorExecuter extends AbstractSchemaConfiguratorExecuter
{
    public function __construct(
        InstanceManagerInterface $instanceManager,
        protected EndpointHelpers $endpointHelpers,
        protected PersistedQueryEndpointSchemaConfigurator $persistedQueryEndpointSchemaConfigurator
    ) {
        parent::__construct(
            $instanceManager,
        );
    }

    /**
     * Initialize the configuration if editing a persisted query
     */
    protected function getCustomPostID(): ?int
    {
        if ($this->endpointHelpers->isRequestingAdminPersistedQueryGraphQLEndpoint()) {
            return (int) $this->endpointHelpers->getAdminPersistedQueryCustomPostID();
        }
        return null;
    }

    protected function getSchemaConfigurator(): SchemaConfiguratorInterface
    {
        return $this->persistedQueryEndpointSchemaConfigurator;
    }
}
