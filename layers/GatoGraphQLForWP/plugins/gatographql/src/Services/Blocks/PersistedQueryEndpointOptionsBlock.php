<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\Blocks;

use GatoGraphQL\GatoGraphQL\Services\BlockCategories\BlockCategoryInterface;
use GatoGraphQL\GatoGraphQL\Services\BlockCategories\PersistedQueryEndpointBlockCategory;

/**
 * Persisted Query Options block
 */
class PersistedQueryEndpointOptionsBlock extends AbstractEndpointOptionsBlock implements PersistedQueryEndpointEditorBlockServiceTagInterface
{
    use MainPluginBlockTrait;

    public final const ATTRIBUTE_NAME_DO_URL_PARAMS_OVERRIDE_GRAPHQL_VARIABLES = 'doURLParamsOverrideGraphQLVariables';

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
        return 'persisted-query-endpoint-options';
    }

    public function getBlockPriority(): int
    {
        return 160;
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
    protected function getBlockContent(array $attributes, string $content): string
    {
        $blockContent = parent::getBlockContent($attributes, $content);

        $blockContentPlaceholder = '<p><strong>%s</strong></p><p>%s</p>';
        $blockContent .= sprintf(
            $blockContentPlaceholder,
            \__('Do URL params override variables?', 'gatographql'),
            $this->getBooleanLabel($attributes[self::ATTRIBUTE_NAME_DO_URL_PARAMS_OVERRIDE_GRAPHQL_VARIABLES] ?? true)
        );

        return $blockContent;
    }
}
