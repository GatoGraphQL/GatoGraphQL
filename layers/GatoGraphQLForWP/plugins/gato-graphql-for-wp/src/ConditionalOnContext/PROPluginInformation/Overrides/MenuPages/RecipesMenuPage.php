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
    ): string {
        $recipeEntryTitle = parent::getRecipeTitleForNavbar($recipeEntryTitle, $recipeEntryIsPRO);
        if (!$recipeEntryIsPRO) {
            return $recipeEntryTitle;
        }
        return PROPluginStaticHelpers::getPROTitle($recipeEntryTitle);
    }

    protected function getRecipeContent(
        string $recipeContent,
        bool $recipeEntryIsPRO,
    ): string {
        $recipeContent = parent::getRecipeContent($recipeContent, $recipeEntryIsPRO);
        if (!$recipeEntryIsPRO) {
            return $recipeContent;
        }
        return sprintf(
            <<<HTML
                <div class="go-pro-highlight">
                    <p>%s %s</p>
                </div>
            HTML,
            \__('This recipe requires features available in the GraphQL API PRO.', 'gato-graphql'),
            PROPluginStaticHelpers::getGoPROToUnlockAnchorHTML('button button-secondary')
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
