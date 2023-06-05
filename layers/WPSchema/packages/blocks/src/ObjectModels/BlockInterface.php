<?php

declare(strict_types=1);

namespace PoPWPSchema\Blocks\ObjectModels;

use PoP\ComponentModel\ObjectModels\TransientObjectInterface;

interface BlockInterface extends TransientObjectInterface
{
    public function getMessage(): string;
}
