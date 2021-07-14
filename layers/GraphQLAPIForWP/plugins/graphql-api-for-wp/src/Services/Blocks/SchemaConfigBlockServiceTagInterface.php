<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks;

interface SchemaConfigBlockServiceTagInterface
{
    public function getSchemaConfigBlockPriority(): int;
}
