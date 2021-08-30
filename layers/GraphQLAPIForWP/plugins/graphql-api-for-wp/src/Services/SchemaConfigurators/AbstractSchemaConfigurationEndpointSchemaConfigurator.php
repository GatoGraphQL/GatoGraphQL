<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators;

abstract class AbstractSchemaConfigurationEndpointSchemaConfigurator extends AbstractEndpointSchemaConfigurator
{
    /**
     * The received customPostID is already the schemaConfigurationID!
     */
    protected function getSchemaConfigurationID(int $customPostID): ?int
    {
        return $customPostID;
    }
}
