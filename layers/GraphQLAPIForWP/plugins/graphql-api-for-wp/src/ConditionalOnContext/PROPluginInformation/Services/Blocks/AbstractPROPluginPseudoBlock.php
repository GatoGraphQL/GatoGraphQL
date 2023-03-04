<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnContext\PROPluginInformation\Services\Blocks;

use GraphQLAPI\GraphQLAPI\Services\Blocks\AbstractBlock;

abstract class AbstractPROPluginPseudoBlock extends AbstractBlock
{
    /**
     * Folder storing all the blocks
     */
    protected function getBlocksFolder(): string
    {
        return 'blocks-pro';
    }

    /**
     * Produce the HTML for dynamic blocks
     *
     * @param array<string,mixed> $attributes
     */
    public function renderBlock(array $attributes, string $content): string
    {
        return '';
    }
}
