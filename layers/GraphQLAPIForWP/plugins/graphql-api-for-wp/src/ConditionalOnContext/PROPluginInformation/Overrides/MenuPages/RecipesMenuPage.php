<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnContext\PROPluginInformation\Overrides\MenuPages;

use GraphQLAPI\GraphQLAPI\ConditionalOnContext\PROPluginInformation\StaticHelpers\PROPluginStaticHelpers;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\RecipesMenuPage as UpstreamRecipesMenuPage;

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
            \__('This recipe requires features available in the GraphQL API PRO.', 'graphql-api'),
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
