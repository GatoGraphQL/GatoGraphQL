<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks;

use GraphQLAPI\GraphQLAPI\ComponentConfiguration;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\Blocks\MainPluginBlockTrait;

class SchemaConfigNamespacingBlock extends AbstractSchemaConfigBlock implements SchemaConfigBlockServiceTagInterface
{
    use MainPluginBlockTrait;
    use OptionsBlockTrait;

    public const ATTRIBUTE_NAME_USE_NAMESPACING = 'useNamespacing';

    protected function getBlockName(): string
    {
        return 'schema-config-namespacing';
    }

    public function getSchemaConfigBlockPriority(): int
    {
        return 10;
    }

    public function getEnablingModule(): ?string
    {
        return SchemaConfigurationFunctionalityModuleResolver::SCHEMA_NAMESPACING;
    }

    /**
     * @param array<string, mixed> $attributes
     */
    public function renderBlock(array $attributes, string $content): string
    {
        // Append "-front" because this style must be used only on the client, not on the admin
        $className = $this->getBlockClassName() . '-front';

        $blockContentPlaceholder = '<p><strong>%s</strong> %s</p>';

        $enabledDisabledLabels = $this->getEnabledDisabledLabels();
        $blockContent = sprintf(
            $blockContentPlaceholder,
            \__('Use namespacing?', 'graphql-api'),
            $enabledDisabledLabels[$attributes[self::ATTRIBUTE_NAME_USE_NAMESPACING] ?? ''] ?? ComponentConfiguration::getSettingsValueLabel()
        );

        $blockContentPlaceholder = <<<EOT
        <div class="%s">
            <h3 class="%s">%s</h3>
            %s
        </div>
EOT;
        return sprintf(
            $blockContentPlaceholder,
            $className . ' ' . $this->getAlignClass(),
            $className . '__title',
            \__('Namespacing', 'graphql-api'),
            $blockContent
        );
    }

    /**
     * Register index.css
     */
    protected function registerEditorCSS(): bool
    {
        return true;
    }
    /**
     * Register style-index.css
     */
    protected function registerCommonStyleCSS(): bool
    {
        return true;
    }
}
