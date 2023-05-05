<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Registries;

use GatoGraphQL\GatoGraphQL\Services\Blocks\BlockInterface;

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
