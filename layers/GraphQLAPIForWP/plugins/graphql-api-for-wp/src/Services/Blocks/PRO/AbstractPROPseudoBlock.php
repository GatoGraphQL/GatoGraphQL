<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks\PRO;

use GraphQLAPI\GraphQLAPI\Services\Blocks\AbstractBlock;

abstract class AbstractPROPseudoBlock extends AbstractBlock
{
    /**
     * Folder storing all the blocks
     */
    protected function getBlocksFolder(): string
    {
        return 'blocks-pro';
    }
}
