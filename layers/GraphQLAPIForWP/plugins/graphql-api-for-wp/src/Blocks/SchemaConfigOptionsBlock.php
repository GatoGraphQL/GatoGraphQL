<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Blocks;

use PoP\AccessControl\Schema\SchemaModes;
use GraphQLAPI\GraphQLAPI\ComponentConfiguration;
use GraphQLAPI\GraphQLAPI\Facades\ModuleRegistryFacade;
use GraphQLAPI\GraphQLAPI\Blocks\GraphQLByPoPBlockTrait;
use GraphQLByPoP\GraphQLServer\Configuration\MutationSchemes;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use GraphQLAPI\GraphQLAPI\BlockCategories\AbstractBlockCategory;
use GraphQLAPI\GraphQLAPI\BlockCategories\SchemaConfigurationBlockCategory;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\OperationalFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;

/**
 * Schema Config Options block
 */
class SchemaConfigOptionsBlock extends AbstractOptionsBlock
{
    use GraphQLByPoPBlockTrait;

    public const ATTRIBUTE_NAME_USE_NAMESPACING = 'useNamespacing';
    public const ATTRIBUTE_NAME_MUTATION_SCHEME = 'mutationScheme';
    public const ATTRIBUTE_NAME_DEFAULT_SCHEMA_MODE = 'defaultSchemaMode';

    // public const ATTRIBUTE_VALUE_USE_NAMESPACING_DEFAULT = 'default';
    public const ATTRIBUTE_VALUE_USE_NAMESPACING_ENABLED = 'enabled';
    public const ATTRIBUTE_VALUE_USE_NAMESPACING_DISABLED = 'disabled';

    protected function getBlockName(): string
    {
        return 'schema-config-options';
    }

    protected function getBlockCategory(): ?AbstractBlockCategory
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        /**
         * @var SchemaConfigurationBlockCategory
         */
        $blockCategory = $instanceManager->getInstance(SchemaConfigurationBlockCategory::class);
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

        $moduleRegistry = ModuleRegistryFacade::getInstance();
        if ($moduleRegistry->isModuleEnabled(SchemaConfigurationFunctionalityModuleResolver::PUBLIC_PRIVATE_SCHEMA)) {
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

        if ($moduleRegistry->isModuleEnabled(SchemaConfigurationFunctionalityModuleResolver::SCHEMA_NAMESPACING)) {
            $useNamespacingLabels = [
                self::ATTRIBUTE_VALUE_USE_NAMESPACING_ENABLED => \__('✅ Yes', 'graphql-api'),
                self::ATTRIBUTE_VALUE_USE_NAMESPACING_DISABLED => \__('❌ No', 'graphql-api'),
            ];
            $blockContent .= sprintf(
                $blockContentPlaceholder,
                \__('Use namespacing?', 'graphql-api'),
                $useNamespacingLabels[$attributes[self::ATTRIBUTE_NAME_USE_NAMESPACING] ?? ''] ?? ComponentConfiguration::getSettingsValueLabel()
            );
        }

        if ($moduleRegistry->isModuleEnabled(OperationalFunctionalityModuleResolver::NESTED_MUTATIONS)) {
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
        $moduleRegistry = ModuleRegistryFacade::getInstance();
        return array_merge(
            parent::getLocalizedData(),
            [
                'isPublicPrivateSchemaEnabled' => $moduleRegistry->isModuleEnabled(SchemaConfigurationFunctionalityModuleResolver::PUBLIC_PRIVATE_SCHEMA),
                'isSchemaNamespacingEnabled' => $moduleRegistry->isModuleEnabled(SchemaConfigurationFunctionalityModuleResolver::SCHEMA_NAMESPACING),
                'isNestedMutationsEnabled' => $moduleRegistry->isModuleEnabled(OperationalFunctionalityModuleResolver::NESTED_MUTATIONS),
            ]
        );
    }

    /**
     * Register index.css
     *
     * @return boolean
     */
    protected function registerEditorCSS(): bool
    {
        return true;
    }
    /**
     * Register style-index.css
     *
     * @return boolean
     */
    protected function registerCommonStyleCSS(): bool
    {
        return true;
    }
}
