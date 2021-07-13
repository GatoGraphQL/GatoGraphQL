<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters;

use GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigOptionsBlock;
use GraphQLAPI\GraphQLAPI\Services\Helpers\BlockHelpers;

abstract class AbstractOptionSchemaConfigurationExecuter extends AbstractSchemaConfigurationExecuter
{
    /**
     * @return array<string, mixed>|null Data inside the block is saved as key (string) => value
     */
    protected function getSchemaConfigOptionsBlockDataItem(int $schemaConfigurationID): ?array
    {
        /** @var BlockHelpers */
        $blockHelpers = $this->instanceManager->getInstance(BlockHelpers::class);
        /**
         * @var SchemaConfigOptionsBlock
         */
        $block = $this->instanceManager->getInstance(SchemaConfigOptionsBlock::class);
        return $blockHelpers->getSingleBlockOfTypeFromCustomPost(
            $schemaConfigurationID,
            $block
        );
    }
}
