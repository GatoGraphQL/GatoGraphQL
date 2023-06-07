<?php

declare(strict_types=1);

namespace GatoGraphQL\TestingSchema\Services\Blocks;

use GatoGraphQL\GatoGraphQL\Services\Blocks\AbstractBlock;

class NotServerSideRegisteredSchemaTestingBlock extends AbstractBlock
{
    use ExtensionBlockTrait;

    protected function getBlockName(): string
    {
        return 'not-server-registered-schema-testing';
    }

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
            \__('This is a block for testing the schema', 'gato-graphql-testing-schema'),
            \__('In particular, to test field <code>CustomPost.blocks</code>, to see that blocks not registered on the server-side display a warning when parsed.', 'gato-graphql-testing-schema'),
        );

        $blockContentPlaceholder = <<<EOT
            <div class="%s">
                <h3 class="%s">%s</h3>
                %s
            </div>
        EOT;
        return sprintf(
            $blockContentPlaceholder,
            $className . ' ' . $this->getAlignClassName(),
            $className . '__title',
            \__('Interactive Schema', 'gato-graphql-testing-schema'),
            $blockContent
        );
    }
}
