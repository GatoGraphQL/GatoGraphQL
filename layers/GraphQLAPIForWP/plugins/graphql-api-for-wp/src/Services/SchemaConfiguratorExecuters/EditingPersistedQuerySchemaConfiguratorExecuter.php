<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfiguratorExecuters;

use GraphQLAPI\GraphQLAPI\Services\Helpers\EndpointHelpers;
use GraphQLAPI\GraphQLAPI\General\RequestParams;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\SchemaConfiguratorInterface;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\PersistedQuerySchemaConfigurator;

class EditingPersistedQuerySchemaConfiguratorExecuter extends AbstractSchemaConfiguratorExecuter
{
    protected EndpointHelpers $endpointHelpers;
    protected PersistedQuerySchemaConfigurator $persistedQuerySchemaConfigurator;

    function __construct(
        EndpointHelpers $endpointHelpers,
        PersistedQuerySchemaConfigurator $persistedQuerySchemaConfigurator
    ) {
        $this->endpointHelpers = $endpointHelpers;
        $this->persistedQuerySchemaConfigurator = $persistedQuerySchemaConfigurator;
    }

    /**
     * Initialize the configuration if editing a persisted query
     */
    protected function getCustomPostID(): ?int
    {
        if ($this->endpointHelpers->isRequestingAdminGraphQLEndpoint() && isset($_REQUEST[RequestParams::PERSISTED_QUERY_ID])) {
            return (int) $_REQUEST[RequestParams::PERSISTED_QUERY_ID];
        }
        return null;
    }

    protected function getSchemaConfigurator(): SchemaConfiguratorInterface
    {
        return $this->persistedQuerySchemaConfigurator;
    }
}
