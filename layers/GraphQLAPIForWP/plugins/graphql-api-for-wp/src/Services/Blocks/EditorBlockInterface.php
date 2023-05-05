<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\Blocks;

interface EditorBlockInterface extends BlockInterface
{
    public function getBlockPriority(): int;
}
