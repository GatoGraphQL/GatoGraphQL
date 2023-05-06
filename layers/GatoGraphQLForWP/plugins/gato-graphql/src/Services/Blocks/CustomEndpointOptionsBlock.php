<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\Blocks;

use GatoGraphQL\GatoGraphQL\Services\BlockCategories\BlockCategoryInterface;
use GatoGraphQL\GatoGraphQL\Services\BlockCategories\CustomEndpointBlockCategory;

/**
 * Endpoint Options block
 */
class CustomEndpointOptionsBlock extends AbstractEndpointOptionsBlock implements EndpointEditorBlockServiceTagInterface
{
    use MainPluginBlockTrait;

    private ?CustomEndpointBlockCategory $customEndpointBlockCategory = null;

    final public function setCustomEndpointBlockCategory(CustomEndpointBlockCategory $customEndpointBlockCategory): void
    {
        $this->customEndpointBlockCategory = $customEndpointBlockCategory;
    }
    final protected function getCustomEndpointBlockCategory(): CustomEndpointBlockCategory
    {
        /** @var CustomEndpointBlockCategory */
        return $this->customEndpointBlockCategory ??= $this->instanceManager->getInstance(CustomEndpointBlockCategory::class);
    }

    protected function getBlockName(): string
    {
        return 'custom-endpoint-options';
    }

    public function getBlockPriority(): int
    {
        return 160;
    }

    protected function getBlockCategory(): ?BlockCategoryInterface
    {
        return $this->getCustomEndpointBlockCategory();
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
}
