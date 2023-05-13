<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ConditionalOnContext\PROPluginInformation\Overrides\MenuPages;

use GatoGraphQL\GatoGraphQL\ConditionalOnContext\PROPluginInformation\StaticHelpers\PROPluginStaticHelpers;
use GatoGraphQL\GatoGraphQL\Services\MenuPages\RecipesMenuPage as UpstreamRecipesMenuPage;

class RecipesMenuPage extends UpstreamRecipesMenuPage
{
    use UsePRODocsMenuPageTrait;

    protected function getRecipeTitleForNavbar(
        string $recipeEntryTitle,
        bool $recipeEntryIsPRO,
        ?string $recipeEntryPROExtensionModule,
    ): string {
        $recipeEntryTitle = parent::getRecipeTitleForNavbar(
            $recipeEntryTitle,
            $recipeEntryIsPRO,
            $recipeEntryPROExtensionModule,
        );
        if (!$recipeEntryIsPRO) {
            return $recipeEntryTitle;
        }
        return PROPluginStaticHelpers::getPROTitle(
            $recipeEntryTitle,
        );
    }

    protected function getRecipeContent(
        string $recipeContent,
        bool $recipeEntryIsPRO,
        ?string $recipeEntryPROExtensionModule,
    ): string {
        $recipeContent = parent::getRecipeContent(
            $recipeContent,
            $recipeEntryIsPRO,
            $recipeEntryPROExtensionModule,
        );
        if (!$recipeEntryIsPRO) {
            return $recipeContent;
        }
        if ($recipeEntryPROExtensionModule !== null) {
            $message = sprintf(
                \__('This recipe requires extension "%s" to be installed.', 'gato-graphql'),
                ''
            );
            $buttonHTML = PROPluginStaticHelpers::getGoPROToUnlockAnchorHTML('button button-secondary');
        } else {
            $message = sprintf(
                \__('This recipe requires features available in the Gato GraphQL PRO.', 'gato-graphql'),
                ''
            );
            $buttonHTML = PROPluginStaticHelpers::getGoPROToUnlockAnchorHTML('button button-secondary');
        }
        return sprintf(
            <<<HTML
                <div class="go-pro-highlight">
                    <p>%s %s</p>
                </div>
            HTML,
            $message,
            $buttonHTML
        ) . $recipeContent;
    }

    /**
     * Enqueue the required assets and initialize the localized scripts
     */
    protected function enqueueAssets(): void
    {
        parent::enqueueAssets();

        $this->enqueuePRODocsAssets();
    }
}
