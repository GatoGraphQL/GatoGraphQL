<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Registries;

use GraphQLAPI\GraphQLAPI\Services\Blocks\AbstractBlock;

interface BlockRegistryInterface
{
    public function addBlock(AbstractBlock $block): void;
    /**
     * @return AbstractBlock[]
     */
    public function getBlocks(): array;
    /**
     * @return AbstractBlock[]
     */
    public function getEnabledBlocks(): array;
}
