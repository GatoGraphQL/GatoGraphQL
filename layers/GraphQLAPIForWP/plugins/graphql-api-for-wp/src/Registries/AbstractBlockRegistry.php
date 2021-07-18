<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Registries;

use GraphQLAPI\GraphQLAPI\Services\Blocks\AbstractBlock;
use PoP\Root\Services\ServiceInterface;

class AbstractBlockRegistry implements BlockRegistryInterface
{
    /**
     * @var AbstractBlock[]
     */
    protected array $blocks = [];

    public function addBlock(AbstractBlock $block): void
    {
        $this->blocks[] = $block;
    }
    /**
     * @return AbstractBlock[]
     */
    public function getBlocks(): array
    {
        return $this->blocks;
    }
    /**
     * @return AbstractBlock[]
     */
    public function getEnabledBlocks(): array
    {
        return array_filter(
            $this->getBlocks(),
            fn (ServiceInterface $service) => $service->isServiceEnabled()
        );
    }
}
