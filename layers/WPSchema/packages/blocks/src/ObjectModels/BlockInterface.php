<?php

declare(strict_types=1);

namespace PoPWPSchema\Blocks\ObjectModels;

use PoP\ComponentModel\ObjectModels\TransientObjectInterface;
use stdClass;

interface BlockInterface extends TransientObjectInterface
{
    public function getName(): string;
    public function getAttributes(): ?stdClass;
    /**
     * @return BlockInterface[]|null
     */
    public function getInnerBlocks(): ?array;
}
