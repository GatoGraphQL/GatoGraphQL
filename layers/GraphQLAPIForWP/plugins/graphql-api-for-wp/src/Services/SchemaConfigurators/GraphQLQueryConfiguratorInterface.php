<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators;

use PoP\Root\Services\ServiceInterface;

interface GraphQLQueryConfiguratorInterface extends ServiceInterface
{
    /**
     * Execute the schema configuration contained within a custom post with certain ID
     */
    public function executeSchemaConfiguration(int $customPostID): void;
}
