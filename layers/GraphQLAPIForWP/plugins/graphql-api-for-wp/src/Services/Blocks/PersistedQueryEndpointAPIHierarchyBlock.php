<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\BlockCategories\PersistedQueryEndpointBlockCategory;
use GraphQLAPI\GraphQLAPI\Services\Blocks\AbstractBlock;
use GraphQLAPI\GraphQLAPI\Services\Blocks\MainPluginBlockTrait;
use GraphQLAPI\GraphQLAPI\Services\Blocks\PersistedQueryEndpointEditorBlockServiceTagInterface;

/**
 * Persisted Query API Hierarchy block
 */
class PersistedQueryEndpointAPIHierarchyBlock extends AbstractBlock implements PersistedQueryEndpointEditorBlockServiceTagInterface
{
    use MainPluginBlockTrait;
    use OptionsBlockTrait;

    public const ATTRIBUTE_NAME_INHERIT_QUERY = 'inheritQuery';

    protected function getBlockName(): string
    {
        return 'persisted-query-api-hierarchy';
    }

    public function getBlockPriority(): int
    {
        return 140;
    }

    public function getEnablingModule(): ?string
    {
        return EndpointFunctionalityModuleResolver::API_HIERARCHY;
    }

    protected function isDynamicBlock(): bool
    {
        return true;
    }

    /**
     * Add the locale language to the localized data?
     */
    protected function addLocalLanguage(): bool
    {
        return true;
    }

    /**
     * Default language for the script/component's documentation
     */
    protected function getDefaultLanguage(): ?string
    {
        // English
        return 'en';
    }

    protected function getBlockCategoryClass(): ?string
    {
        return PersistedQueryEndpointBlockCategory::class;
    }

    /**
     * @param array<string, mixed> $attributes
     */
    public function renderBlock(array $attributes, string $content): string
    {
        // Append "-front" because this style must be used only on the client, not on the admin
        $className = $this->getBlockClassName() . '-front';

        $blockContentPlaceholder = '<p><strong>%s</strong></p><p>%s</p>';
        $blockContent = sprintf(
            $blockContentPlaceholder,
            \__('Inherit query from ancestor(s):', 'graphql-api'),
            $this->getBooleanLabel($attributes[self::ATTRIBUTE_NAME_INHERIT_QUERY] ?? false)
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
            \__('API Hierarchy', 'graphql-api'),
            $blockContent
        );
    }
}
