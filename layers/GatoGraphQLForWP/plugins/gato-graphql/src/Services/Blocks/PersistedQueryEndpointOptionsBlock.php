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

    public final const ATTRIBUTE_NAME_ACCEPT_VARIABLES_AS_URL_PARAMS = 'acceptVariablesAsURLParams';

    private ?PersistedQueryEndpointBlockCategory $persistedQueryEndpointBlockCategory = null;

    final public function setPersistedQueryEndpointBlockCategory(PersistedQueryEndpointBlockCategory $persistedQueryEndpointBlockCategory): void
    {
        $this->persistedQueryEndpointBlockCategory = $persistedQueryEndpointBlockCategory;
    }
    final protected function getPersistedQueryEndpointBlockCategory(): PersistedQueryEndpointBlockCategory
    {
        /** @var PersistedQueryEndpointBlockCategory */
        return $this->persistedQueryEndpointBlockCategory ??= $this->instanceManager->getInstance(PersistedQueryEndpointBlockCategory::class);
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
            \__('Accept variables as URL params:', 'gato-graphql'),
            $this->getBooleanLabel($attributes[self::ATTRIBUTE_NAME_ACCEPT_VARIABLES_AS_URL_PARAMS] ?? true)
        );

        return $blockContent;
    }
}
