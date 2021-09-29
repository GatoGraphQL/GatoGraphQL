<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Registries;

use GraphQLAPI\GraphQLAPI\Services\Blocks\BlockInterface;

interface BlockRegistryInterface
{
    public function addBlock(BlockInterface $block): void;
    /**
     * @return BlockInterface[]
     */
    public function getBlocks(): array;
    /**
     * @return BlockInterface[]
     */
    public function getEnabledBlocks(): array;
}
