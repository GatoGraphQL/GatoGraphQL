<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks;

use GraphQLAPI\GraphQLAPI\ComponentConfiguration;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaTypeModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\Blocks\MainPluginBlockTrait;

class SchemaConfigAdminSchemaBlock extends AbstractSchemaConfigBlock implements SchemaConfigBlockServiceTagInterface
{
    use MainPluginBlockTrait;
    use OptionsBlockTrait;

    public const ATTRIBUTE_NAME_ENABLE_ADMIN_SCHEMA = 'enableAdminSchema';

    protected function getBlockName(): string
    {
        return 'schema-config-admin-schema';
    }

    public function getSchemaConfigBlockPriority(): int
    {
        return 40;
    }

    public function getEnablingModule(): ?string
    {
        return SchemaTypeModuleResolver::SCHEMA_ADMIN_SCHEMA;
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
            \__('Add admin fields to schema?', 'graphql-api'),
            $enabledDisabledLabels[$attributes[self::ATTRIBUTE_NAME_ENABLE_ADMIN_SCHEMA] ?? ''] ?? ComponentConfiguration::getSettingsValueLabel()
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
            \__('Schema for the Admin', 'graphql-api'),
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
