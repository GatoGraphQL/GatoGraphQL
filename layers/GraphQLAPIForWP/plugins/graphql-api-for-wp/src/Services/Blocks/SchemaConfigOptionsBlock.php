<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks;

use GraphQLAPI\GraphQLAPI\ComponentConfiguration;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\OperationalFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaTypeModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\BlockCategories\AbstractBlockCategory;
use GraphQLAPI\GraphQLAPI\Services\BlockCategories\SchemaConfigurationBlockCategory;
use GraphQLAPI\GraphQLAPI\Services\Blocks\GraphQLByPoPBlockTrait;
use GraphQLByPoP\GraphQLServer\Configuration\MutationSchemes;
use PoP\AccessControl\Schema\SchemaModes;

/**
 * Schema Config Options block
 */
class SchemaConfigOptionsBlock extends AbstractOptionsBlock
{
    use GraphQLByPoPBlockTrait;

    public const ATTRIBUTE_NAME_ENABLE_ADMIN_SCHEMA = 'enableAdminSchema';
    public const ATTRIBUTE_NAME_USE_NAMESPACING = 'useNamespacing';
    public const ATTRIBUTE_NAME_MUTATION_SCHEME = 'mutationScheme';
    public const ATTRIBUTE_NAME_DEFAULT_SCHEMA_MODE = 'defaultSchemaMode';

    // public const ATTRIBUTE_VALUE_DEFAULT = 'default';
    public const ATTRIBUTE_VALUE_ENABLED = 'enabled';
    public const ATTRIBUTE_VALUE_DISABLED = 'disabled';

    protected function getBlockName(): string
    {
        return 'schema-config-options';
    }

    protected function getBlockCategory(): ?AbstractBlockCategory
    {
        /**
         * @var SchemaConfigurationBlockCategory
         */
        $blockCategory = $this->instanceManager->getInstance(SchemaConfigurationBlockCategory::class);
        return $blockCategory;
    }

    protected function isDynamicBlock(): bool
    {
        return true;
    }

    /**
     * @param array<string, mixed> $attributes
     */
    public function renderBlock(array $attributes, string $content): string
    {
        // Append "-front" because this style must be used only on the client, not on the admin
        $className = $this->getBlockClassName() . '-front';

        $blockContentPlaceholder = '<p><strong>%s</strong> %s</p>';
        $blockContent = '';

        if ($this->moduleRegistry->isModuleEnabled(SchemaConfigurationFunctionalityModuleResolver::PUBLIC_PRIVATE_SCHEMA)) {
            $schemaModeLabels = [
                SchemaModes::PUBLIC_SCHEMA_MODE => \__('Public', 'graphql-api'),
                SchemaModes::PRIVATE_SCHEMA_MODE => \__('Private', 'graphql-api'),
            ];
            $blockContent .= sprintf(
                $blockContentPlaceholder,
                \__('Public/Private Schema:', 'graphql-api'),
                $schemaModeLabels[$attributes[self::ATTRIBUTE_NAME_DEFAULT_SCHEMA_MODE] ?? ''] ?? ComponentConfiguration::getSettingsValueLabel()
            );
        }

        $enabledDisabledLabels = [
            self::ATTRIBUTE_VALUE_ENABLED => \__('✅ Yes', 'graphql-api'),
            self::ATTRIBUTE_VALUE_DISABLED => \__('❌ No', 'graphql-api'),
        ];
        if ($this->moduleRegistry->isModuleEnabled(SchemaTypeModuleResolver::SCHEMA_ADMIN_SCHEMA)) {
            $blockContent .= sprintf(
                $blockContentPlaceholder,
                \__('Add admin fields to schema?', 'graphql-api'),
                $enabledDisabledLabels[$attributes[self::ATTRIBUTE_NAME_ENABLE_ADMIN_SCHEMA] ?? ''] ?? ComponentConfiguration::getSettingsValueLabel()
            );
        }

        if ($this->moduleRegistry->isModuleEnabled(SchemaConfigurationFunctionalityModuleResolver::SCHEMA_NAMESPACING)) {
            $blockContent .= sprintf(
                $blockContentPlaceholder,
                \__('Use namespacing?', 'graphql-api'),
                $enabledDisabledLabels[$attributes[self::ATTRIBUTE_NAME_USE_NAMESPACING] ?? ''] ?? ComponentConfiguration::getSettingsValueLabel()
            );
        }

        if ($this->moduleRegistry->isModuleEnabled(OperationalFunctionalityModuleResolver::NESTED_MUTATIONS)) {
            $mutationSchemeLabels = [
                MutationSchemes::STANDARD => \__('❌ Do not enable nested mutations', 'graphql-api'),
                MutationSchemes::NESTED_WITH_REDUNDANT_ROOT_FIELDS => \__('✅ Nested mutations enabled, keeping all mutation fields in the root type', 'graphql-api'),
                MutationSchemes::NESTED_WITHOUT_REDUNDANT_ROOT_FIELDS => \__('✳️ Nested mutations enabled, removing the redundant mutation fields from the root type', 'graphql-api'),
            ];
            $blockContent .= sprintf(
                $blockContentPlaceholder,
                \__('Mutation Scheme', 'graphql-api'),
                $mutationSchemeLabels[$attributes[self::ATTRIBUTE_NAME_MUTATION_SCHEME] ?? ''] ?? ComponentConfiguration::getSettingsValueLabel()
            );
        }

        // If all modules are disabled and there are not options...
        if ($blockContent == '') {
            $blockContent = sprintf(
                '<p>%s</p>',
                \__('(Nothing here: All Schema Configuration options are disabled)', 'graphql-api')
            );
        }

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
            \__('Options', 'graphql-api'),
            $blockContent
        );
    }

    /**
     * Pass localized data to the block
     *
     * @return array<string, mixed>
     */
    protected function getLocalizedData(): array
    {
        return array_merge(
            parent::getLocalizedData(),
            [
                'isPublicPrivateSchemaEnabled' => $this->moduleRegistry->isModuleEnabled(SchemaConfigurationFunctionalityModuleResolver::PUBLIC_PRIVATE_SCHEMA),
                'isAdminSchemaEnabled' => $this->moduleRegistry->isModuleEnabled(SchemaTypeModuleResolver::SCHEMA_ADMIN_SCHEMA),
                'isSchemaNamespacingEnabled' => $this->moduleRegistry->isModuleEnabled(SchemaConfigurationFunctionalityModuleResolver::SCHEMA_NAMESPACING),
                'isNestedMutationsEnabled' => $this->moduleRegistry->isModuleEnabled(OperationalFunctionalityModuleResolver::NESTED_MUTATIONS),
            ]
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
