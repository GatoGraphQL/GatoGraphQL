<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\Blocks;

use GatoGraphQL\GatoGraphQL\Constants\BlockAttributeNames;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\ClientFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\Services\BlockCategories\BlockCategoryInterface;
use GatoGraphQL\GatoGraphQL\Services\BlockCategories\CustomEndpointBlockCategory;

class EndpointVoyagerBlock extends AbstractBlock implements EndpointEditorBlockServiceTagInterface
{
    use MainPluginBlockTrait;
    use OptionsBlockTrait;

    private ?CustomEndpointBlockCategory $customEndpointBlockCategory = null;

    final public function setCustomEndpointBlockCategory(CustomEndpointBlockCategory $customEndpointBlockCategory): void
    {
        $this->customEndpointBlockCategory = $customEndpointBlockCategory;
    }
    final protected function getCustomEndpointBlockCategory(): CustomEndpointBlockCategory
    {
        if ($this->customEndpointBlockCategory === null) {
            /** @var CustomEndpointBlockCategory */
            $customEndpointBlockCategory = $this->instanceManager->getInstance(CustomEndpointBlockCategory::class);
            $this->customEndpointBlockCategory = $customEndpointBlockCategory;
        }
        return $this->customEndpointBlockCategory;
    }

    protected function getBlockName(): string
    {
        return 'endpoint-voyager';
    }

    public function getEnablingModule(): ?string
    {
        return ClientFunctionalityModuleResolver::INTERACTIVE_SCHEMA_FOR_CUSTOM_ENDPOINTS;
    }

    public function getBlockPriority(): int
    {
        return 120;
    }

    protected function getBlockCategory(): ?BlockCategoryInterface
    {
        return $this->getCustomEndpointBlockCategory();
    }

    protected function isDynamicBlock(): bool
    {
        return true;
    }

    /**
     * @param array<string,mixed> $attributes
     */
    public function renderBlock(array $attributes, string $content): string
    {
        // Append "-front" because this style must be used only on the client, not on the admin
        $className = $this->getBlockClassName() . '-front';

        $blockContentPlaceholder = '<p><strong>%s</strong></p><p>%s</p>';
        $blockContent = sprintf(
            $blockContentPlaceholder,
            \__('Expose the Interactive Schema client?', 'gato-graphql'),
            $this->getBooleanLabel($attributes[BlockAttributeNames::IS_ENABLED] ?? true)
        );

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
            \__('Interactive Schema', 'gato-graphql'),
            $blockContent
        );
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
