<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Registries;

use GraphQLAPI\GraphQLAPI\Services\Blocks\AbstractBlock;

class SchemaConfigBlockRegistry implements SchemaConfigBlockRegistryInterface
{
    /**
     * @var AbstractBlock[]
     */
    protected array $schemaConfigBlocks = [];

    public function addSchemaConfigBlock(AbstractBlock $schemaConfigBlock): void
    {
        $this->schemaConfigBlocks[] = $schemaConfigBlock;
    }
    /**
     * @return AbstractBlock[]
     */
    public function getSchemaConfigBlocks(): array
    {
        return $this->schemaConfigBlocks;
    }
}
