<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks;

interface PriorityBlockInterface
{
    public function getBlockPriority(): int;
}
