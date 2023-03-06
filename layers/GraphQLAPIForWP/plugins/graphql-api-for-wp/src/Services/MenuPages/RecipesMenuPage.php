<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\MenuPages;

use GraphQLAPI\GraphQLAPI\App;
use GraphQLAPI\GraphQLAPI\Constants\RequestParams;
use GraphQLAPI\GraphQLAPI\ContentProcessors\ContentParserOptions;
use GraphQLAPI\GraphQLAPI\ContentProcessors\PluginMarkdownContentRetrieverTrait;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\AbstractDocsMenuPage;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\OpenInModalTriggerMenuPageTrait;

class RecipesMenuPage extends AbstractDocsMenuPage
{
    use OpenInModalTriggerMenuPageTrait;
    use PluginMarkdownContentRetrieverTrait;

    public function getMenuPageSlug(): string
    {
        return 'recipes';
    }

    protected function useTabpanelForContent(): bool
    {
        return false;
    }

    protected function getContentToPrint(): string
    {
        $recipeEntries = [
            [
                'intro',
                'Intro',
            ],
            [
                'sample-recipe-1',
                'Sample Recipe 1',
            ],
            [
                'sample-recipe-2',
                'Sample Recipe 2',
            ],
        ];
        // By default, focus on the first recipe
        $activeRecipeName = $recipeEntries[0][0];
        // If passing a tab, focus on that one, if the module exists
        $tab = App::query(RequestParams::TAB);
        if ($tab !== null) {
            $recipeNames = array_map(
                fn (array $recipeEntry) => $recipeEntry[0],
                $recipeEntries
            );
            if (in_array($tab, $recipeNames)) {
                $activeRecipeName = $tab;
            }
        }
        $class = 'wrap vertical-tabs graphql-api-tabpanel';
        
        $markdownContent = sprintf(
            <<<HTML
            <div id="%s" class="%s">
                <h1>%s</h1>
                <div class="nav-tab-container">
                    <!-- Tabs -->
                    <h2 class="nav-tab-wrapper">
            HTML,
            'graphql-api-recipes',
            $class,
            \__('GraphQL API - Documentation: Use Cases, Best Practices, and Recipes', 'graphql-api')
        );

        foreach ($recipeEntries as $recipeEntry) {
            $recipeEntryName = $recipeEntry[0];
            $recipeEntryTitle = $recipeEntry[1];
            $markdownContent .= sprintf(
                '<a href="#%s" class="nav-tab %s">%s</a>',
                $recipeEntryName,
                $recipeEntryName === $activeRecipeName ? 'nav-tab-active' : '',
                $recipeEntryTitle
            );
        }

        $markdownContent .= sprintf(
            <<<HTML
                            </h2>
                            <div class="nav-tab-content">
            HTML,
            'graphql-api-recipes',
            $class,
            \__('GraphQL API — Recipes', 'graphql-api')
        );

        foreach ($recipeEntries as $recipeEntry) {
            $recipeEntryName = $recipeEntry[0];
            $recipeEntryTitle = $recipeEntry[1];

            $recipeContent = $this->getMarkdownContent(
                'recipes/' . $recipeEntryName,
                '',
                [
                    ContentParserOptions::TAB_CONTENT => false,
                ]
            ) ?? sprintf(
                '<p>%s</p>',
                sprintf(
                    \__('Oops, there was a problem loading recipe "%s"', 'graphql-api'),
                    $recipeEntryTitle
                )
            );

            $markdownContent .= sprintf(
                <<<HTML
                    <div id="%s" class="%s" style="%s">
                        <!--<h2>%s</h2><hr/>-->
                        %s
                    </div>
                HTML,
                $recipeEntryName,
                'tab-content',
                sprintf(
                    'display: %s;',
                    $recipeEntryName === $activeRecipeName ? 'block' : 'none'
                ),
                $recipeEntryTitle,
                $recipeContent
            );
        }

        $markdownContent .= <<<HTML
                </div> <!-- class="nav-tab-content" -->
            </div> <!-- class="nav-tab-container" -->
        </div>
        HTML;
        return $markdownContent;
    }

    /**
     * Enqueue the required assets and initialize the localized scripts
     */
    protected function enqueueAssets(): void
    {
        parent::enqueueAssets();

        $this->enqueueTabpanelAssets();
    }
}
