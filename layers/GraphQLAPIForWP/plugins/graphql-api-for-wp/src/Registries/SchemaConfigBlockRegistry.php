<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Registries;

use GraphQLAPI\GraphQLAPI\Services\Blocks\AbstractBlock;
use PoP\Root\Services\ServiceInterface;

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
        // Only enabled services
        return array_filter(
            $this->schemaConfigBlocks,
            fn (ServiceInterface $service) => $service->isServiceEnabled()
        );
    }
}
