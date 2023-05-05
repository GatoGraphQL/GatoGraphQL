<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\Blocks;

use GatoGraphQL\GatoGraphQL\Constants\BlockAttributeNames;

/**
 * Endpoint (custom endpoint and persisted query) Options block
 */
abstract class AbstractEndpointOptionsBlock extends AbstractBlock
{
    use OptionsBlockTrait;

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
            \__('Options', 'gato-graphql'),
            $this->getBlockContent($attributes, $content)
        );
    }

    /**
     * @param array<string,mixed> $attributes
     */
    protected function getBlockContent(array $attributes, string $content): string
    {
        $blockContentPlaceholder = '<p><strong>%s</strong> %s</p>';
        return sprintf(
            $blockContentPlaceholder,
            \__('Enabled:', 'gato-graphql'),
            $this->getBooleanLabel($attributes[BlockAttributeNames::IS_ENABLED] ?? true)
        );
    }
}
