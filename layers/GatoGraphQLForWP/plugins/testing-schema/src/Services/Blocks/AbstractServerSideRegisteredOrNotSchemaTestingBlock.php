<?php

declare(strict_types=1);

namespace GatoGraphQL\TestingSchema\Services\Blocks;

use GatoGraphQL\GatoGraphQL\Services\Blocks\AbstractBlock;

abstract class AbstractServerSideRegisteredOrNotSchemaTestingBlock extends AbstractBlock
{
    use ExtensionBlockTrait;

    protected function isDynamicBlock(): bool
    {
        return true;
    }

    /**
     * @param array<string,mixed> $attributes
     */
    public function renderBlock(array $attributes, string $content): string
    {
        // Append "-front" because this style must be used only on the client, not on the admin
        $className = $this->getBlockClassName() . '-front';

        $blockContentPlaceholder = '<p><strong>%s</strong></p><p>%s</p>';
        $blockContent = sprintf(
            $blockContentPlaceholder,
            \__('This is a block for testing the schema', 'gatographql-testing-schema'),
            \__('In particular, to test field <code>CustomPost.blocks</code>, to see that blocks not registered on the server-side display a warning when parsed.', 'gatographql-testing-schema'),
        );

        $blockContentPlaceholder = '<div class="%s"><h3 class="%s">%s</h3>%s</div>';
        return sprintf(
            $blockContentPlaceholder,
            $className . ' ' . $this->getAlignClassName(),
            $className . '__title',
            \__('Gato GraphQL: Block for testing the schema', 'gatographql-testing-schema'),
            $blockContent
        );
    }
}
