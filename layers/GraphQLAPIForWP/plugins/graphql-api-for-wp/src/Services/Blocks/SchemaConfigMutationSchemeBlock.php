<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks;

use GraphQLAPI\GraphQLAPI\ComponentConfiguration;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\OperationalFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\BlockCategories\AbstractBlockCategory;
use GraphQLAPI\GraphQLAPI\Services\BlockCategories\SchemaConfigurationBlockCategory;
use GraphQLAPI\GraphQLAPI\Services\Blocks\MainPluginBlockTrait;
use GraphQLByPoP\GraphQLServer\Configuration\MutationSchemes;

class SchemaConfigMutationSchemeBlock extends AbstractOptionsBlock implements SchemaConfigBlockServiceTagInterface
{
    use MainPluginBlockTrait;

    public const ATTRIBUTE_NAME_MUTATION_SCHEME = 'mutationScheme';

    protected function getBlockName(): string
    {
        return 'schema-config-mutation-scheme';
    }

    public function getSchemaConfigBlockPriority(): int
    {
        return 20;
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

    public function getEnablingModule(): ?string
    {
        return OperationalFunctionalityModuleResolver::NESTED_MUTATIONS;
    }

    /**
     * @param array<string, mixed> $attributes
     */
    public function renderBlock(array $attributes, string $content): string
    {
        // Append "-front" because this style must be used only on the client, not on the admin
        $className = $this->getBlockClassName() . '-front';

        $blockContentPlaceholder = '<p><strong>%s</strong> %s</p>';

        $mutationSchemeLabels = [
            MutationSchemes::STANDARD => \__('❌ Do not enable nested mutations', 'graphql-api'),
            MutationSchemes::NESTED_WITH_REDUNDANT_ROOT_FIELDS => \__('✅ Nested mutations enabled, keeping all mutation fields in the root type', 'graphql-api'),
            MutationSchemes::NESTED_WITHOUT_REDUNDANT_ROOT_FIELDS => \__('✳️ Nested mutations enabled, removing the redundant mutation fields from the root type', 'graphql-api'),
        ];
        $blockContent = sprintf(
            $blockContentPlaceholder,
            \__('Mutation Scheme', 'graphql-api'),
            $mutationSchemeLabels[$attributes[self::ATTRIBUTE_NAME_MUTATION_SCHEME] ?? ''] ?? ComponentConfiguration::getSettingsValueLabel()
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
            \__('Mutation Scheme', 'graphql-api'),
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
