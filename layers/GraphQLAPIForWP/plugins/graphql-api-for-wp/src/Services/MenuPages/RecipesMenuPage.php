<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\MenuPages;

use GraphQLAPI\GraphQLAPI\App;
use GraphQLAPI\GraphQLAPI\Constants\RequestParams;
use GraphQLAPI\GraphQLAPI\ContentProcessors\ContentParserOptions;
use GraphQLAPI\GraphQLAPI\ContentProcessors\NoDocsFolderPluginMarkdownContentRetrieverTrait;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\AbstractDocsMenuPage;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\OpenInModalTriggerMenuPageTrait;

class RecipesMenuPage extends AbstractDocsMenuPage
{
    use OpenInModalTriggerMenuPageTrait;
    use NoDocsFolderPluginMarkdownContentRetrieverTrait;

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
                'docs/recipes',
                'Intro',
            ],
            [
                'sample-recipe-1',
                'docs/recipes',
                'Sample Recipe 1',
            ],
            [
                'sample-recipe-2',
                'docs/recipes',
                'Sample Recipe 2',
            ],
            [
                'sample-recipe-3',
                'docs-pro/recipes',
                'Sample Recipe 3',
            ],
            [
                'sample-recipe-4',
                'docs-pro/recipes',
                'Sample Recipe 4',
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
            // $recipeEntryRelativePathDir = $recipeEntry[1];
            $recipeEntryTitle = $recipeEntry[2];
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
            $recipeEntryRelativePathDir = $recipeEntry[1];
            $recipeEntryTitle = $recipeEntry[2];

            $recipeContent = $this->getMarkdownContent(
                $recipeEntryName,
                $recipeEntryRelativePathDir,
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

            // Hide the title from the content, as it's already shown below
            $recipeContent = str_replace(
                '<h1>',
                '<h1 style="display: none;">',
                $recipeContent
            );

            // @todo Remove this
            $recipeContent = str_replace(
                '<ul>',
                sprintf('<ul style="%s">', 'list-style: initial; padding-left: 15px;'),
                $recipeContent
            );

            $markdownContent .= sprintf(
                <<<HTML
                    <div id="%s" class="%s" style="%s">
                        <h2>%s</h2><hr/>
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
