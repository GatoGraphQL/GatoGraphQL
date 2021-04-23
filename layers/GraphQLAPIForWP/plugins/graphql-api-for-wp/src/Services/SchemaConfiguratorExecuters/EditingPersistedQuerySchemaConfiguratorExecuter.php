<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfiguratorExecuters;

use GraphQLAPI\GraphQLAPI\Services\Helpers\EndpointHelpers;
use GraphQLAPI\GraphQLAPI\Constants\RequestParams;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\SchemaConfiguratorInterface;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\PersistedQuerySchemaConfigurator;

class EditingPersistedQuerySchemaConfiguratorExecuter extends AbstractSchemaConfiguratorExecuter
{
    function __construct(
        protected EndpointHelpers $endpointHelpers,
        protected PersistedQuerySchemaConfigurator $persistedQuerySchemaConfigurator
    ) {
    }

    /**
     * Initialize the configuration if editing a persisted query
     */
    protected function getCustomPostID(): string | int | null
    {
        if ($this->endpointHelpers->isRequestingAdminPersistedQueryGraphQLEndpoint()) {
            return $this->endpointHelpers->getAdminPersistedQueryCustomPostID();
        }
        return null;
    }

    protected function getSchemaConfigurator(): SchemaConfiguratorInterface
    {
        return $this->persistedQuerySchemaConfigurator;
    }
}
