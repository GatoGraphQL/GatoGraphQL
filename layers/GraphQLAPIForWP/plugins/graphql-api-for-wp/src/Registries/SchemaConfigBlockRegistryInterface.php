<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Registries;

use GraphQLAPI\GraphQLAPI\Services\Blocks\AbstractBlock;

interface SchemaConfigBlockRegistryInterface
{
    public function addSchemaConfigBlock(AbstractBlock $schemaConfigBlock): void;
    /**
     * @return AbstractBlock[]
     */
    public function getSchemaConfigBlocks(): array;
}
