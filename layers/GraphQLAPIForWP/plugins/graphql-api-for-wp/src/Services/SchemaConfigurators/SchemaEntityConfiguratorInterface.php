<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators;

use PoP\Root\Services\ServiceInterface;

interface SchemaEntityConfiguratorInterface extends ServiceInterface
{
    /**
     * Execute the schema configuration for entities (fields, directives, etc),
     * retrieving the data from the custom post with certain ID
     */
    public function executeSchemaEntityConfiguration(int $customPostID): void;
}
