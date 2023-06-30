<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\Blocks;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\EndpointConfigurationFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\Services\BlockCategories\BlockCategoryInterface;
use GatoGraphQL\GatoGraphQL\Services\BlockCategories\PersistedQueryEndpointBlockCategory;

/**
 * Persisted Query API Hierarchy block
 */
class PersistedQueryEndpointAPIHierarchyBlock extends AbstractBlock implements PersistedQueryEndpointEditorBlockServiceTagInterface
{
    use MainPluginBlockTrait;
    use OptionsBlockTrait;

    public final const ATTRIBUTE_NAME_INHERIT_QUERY = 'inheritQuery';

    private ?PersistedQueryEndpointBlockCategory $persistedQueryEndpointBlockCategory = null;

    final public function setPersistedQueryEndpointBlockCategory(PersistedQueryEndpointBlockCategory $persistedQueryEndpointBlockCategory): void
    {
        $this->persistedQueryEndpointBlockCategory = $persistedQueryEndpointBlockCategory;
    }
    final protected function getPersistedQueryEndpointBlockCategory(): PersistedQueryEndpointBlockCategory
    {
        if ($this->persistedQueryEndpointBlockCategory === null) {
            /** @var PersistedQueryEndpointBlockCategory */
            $persistedQueryEndpointBlockCategory = $this->instanceManager->getInstance(PersistedQueryEndpointBlockCategory::class);
            $this->persistedQueryEndpointBlockCategory = $persistedQueryEndpointBlockCategory;
        }
        return $this->persistedQueryEndpointBlockCategory;
    }

    protected function getBlockName(): string
    {
        return 'persisted-query-endpoint-api-hierarchy';
    }

    public function getBlockPriority(): int
    {
        return 140;
    }

    public function getEnablingModule(): ?string
    {
        return EndpointConfigurationFunctionalityModuleResolver::API_HIERARCHY;
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

    /**
     * Register style-index.css
     */
    protected function registerCommonStyleCSS(): bool
    {
        return true;
    }

    protected function getBlockCategory(): ?BlockCategoryInterface
    {
        return $this->getPersistedQueryEndpointBlockCategory();
    }

    /**
     * @param array<string,mixed> $attributes
     */
    public function renderBlock(array $attributes, string $content): string
    {
        // Append "-front" because this style must be used only on the client, not on the admin
        $className = $this->getBlockClassName() . '-front';

        /**
         * If there are no attributes, it's because the post has no parent.
         * Then show a message, that the API hierarchy is disabled.
         *
         * This works at the beginning only. If the user has set a parent,
         * and then removes it, this section will then show, even though it's not valid.
         *
         * The issue is that we don't receive the postID here, so we can't check
         * if the post has a parent or not! Alternatively, if we can update the state
         * of the block when `queryPostParent` changes in persisted-query-endpoint-api-hierarchy.js,
         * then this data could be stored in the block and obtained as an attribute.
         *
         * @todo Fix issue
         */
        if (!$attributes) {
            $blockContentPlaceholder = '<p><em>%s</em></p>';
            $blockContent = sprintf(
                $blockContentPlaceholder,
                \__('This section is not enabled, since the persisted query has no ancestor.', 'gato-graphql')
            );
        } else {
            $blockContentPlaceholder = '<p><strong>%s</strong></p><p>%s</p>';
            $blockContent = sprintf(
                $blockContentPlaceholder,
                \__('Inherit query from ancestor(s):', 'gato-graphql'),
                $this->getBooleanLabel($attributes[self::ATTRIBUTE_NAME_INHERIT_QUERY] ?? false)
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
            $className . ' ' . $this->getAlignClassName(),
            $className . '__title',
            \__('API Hierarchy', 'gato-graphql'),
            $blockContent
        );
    }
}
