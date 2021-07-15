<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators;

use PoP\Root\Services\ServiceInterface;

interface SchemaConfiguratorInterface extends ServiceInterface
{
    /**
     * Execute the schema configuration contained in the custom post with certain ID
     */
    public function executeSchemaConfiguration(int $customPostID): void;
}
