<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\Blocks;

abstract class AbstractSchemaConfigCustomizableConfigurationBlock extends AbstractSchemaConfigBlock
{
    public final const ATTRIBUTE_NAME_CUSTOMIZE_CONFIGURATION = 'customizeConfiguration';

    /**
     * @param array<string,mixed> $attributes
     */
    final public function renderBlock(array $attributes, string $content): string
    {
        // Append "-front" because this style must be used only on the client, not on the admin
        $className = $this->getBlockClassName() . '-front';

        $blockContentPlaceholder = '<p><strong>%s</strong></p><p>%s</p>';

        $customizeConfiguration = $attributes[self::ATTRIBUTE_NAME_CUSTOMIZE_CONFIGURATION] ?? false;
        $blockContent = sprintf(
            $blockContentPlaceholder,
            $this->__('Customize configuration? (Or use default from Settings?)', 'gato-graphql'),
            !$customizeConfiguration
                ? sprintf('🟡 %s', $this->__('Use configuration from Settings', 'gato-graphql'))
                : sprintf('🟢 %s', $this->__('Use custom configuration', 'gato-graphql'))
        );

        $blockContentPlaceholder = <<<EOT
            <div class="customizable-configuration %s">
                <div class="customizable-configuration-header">
                    <h3 class="%s">%s</h3>
                    %s
                </div>
                <hr/>
                <div class="customizable-configuration-body" style="%s">
                    %s
                </div>
            </div>
        EOT;
        return sprintf(
            $blockContentPlaceholder,
            $className . ' ' . $this->getAlignClassName(),
            $className . '__title',
            $this->getBlockTitle(),
            $blockContent,
            /**
             * Hardcode style to disable the inputs, same as style in block.
             * @see layers/GatoGraphQLForWP/plugins/gato-graphql/packages/components/src/components/base-styles/_mixins.scss
             */
            $customizeConfiguration ? '' : 'pointer-events: none; opacity: 0.4;',
            $this->doRenderBlock($attributes, $content)
        );
    }

    abstract protected function getBlockTitle(): string;
    /**
     * @param array<string,mixed> $attributes
     */
    abstract protected function doRenderBlock(array $attributes, string $content): string;
}
