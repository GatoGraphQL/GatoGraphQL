<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Registries;

use GraphQLAPI\GraphQLAPI\Services\Blocks\BlockInterface;
use PoP\Root\Services\ServiceInterface;

abstract class AbstractBlockRegistry implements BlockRegistryInterface
{
    /**
     * @var BlockInterface[]
     */
    protected array $blocks = [];

    public function addBlock(BlockInterface $block): void
    {
        $this->blocks[] = $block;
    }
    /**
     * @return BlockInterface[]
     */
    public function getBlocks(): array
    {
        return $this->blocks;
    }
    /**
     * @return BlockInterface[]
     */
    public function getEnabledBlocks(): array
    {
        return array_filter(
            $this->getBlocks(),
            fn (ServiceInterface $service) => $service->isServiceEnabled()
        );
    }
}
