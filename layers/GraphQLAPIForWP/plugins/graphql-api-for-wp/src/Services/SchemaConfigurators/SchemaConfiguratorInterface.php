<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators;

interface SchemaConfiguratorInterface
{
    /**
     * Execute the schema configuration contained in the custom post with certain ID
     *
     * @param integer $customPostID
     * @return void
     */
    public function executeSchemaConfiguration(int $customPostID): void;
}
