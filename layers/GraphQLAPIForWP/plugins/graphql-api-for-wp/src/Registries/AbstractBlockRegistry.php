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
        // Only enabled services
        return array_filter(
            $this->blocks,
            fn (ServiceInterface $service) => $service->isServiceEnabled()
        );
    }
}
