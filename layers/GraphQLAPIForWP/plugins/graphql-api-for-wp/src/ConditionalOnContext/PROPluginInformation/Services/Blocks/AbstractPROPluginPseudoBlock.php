<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ConditionalOnContext\PROPluginInformation\Services\Blocks;

use GatoGraphQL\GatoGraphQL\Services\Blocks\AbstractBlock;

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
