<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\MenuPages;

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
        $markdownContent = '';
        $recipeEntries = [
            [
                'sample-recipe-1',
                'Any built-in scalar',
            ],
            [
                'sample-recipe-2',
                'OneOf Input Object',
            ],
        ];
        foreach ($recipeEntries as $recipeEntry) {
            $markdownContent .= $this->getMarkdownContent(
                'recipes/' . $recipeEntry[0],
                '',
                [
                    ContentParserOptions::TAB_CONTENT => false,
                ]
            ) ?? sprintf(
                '<p>%s</p>',
                sprintf(
                    \__('Oops, there was a problem loading recipe "%s"', 'graphql-api'),
                    $recipeEntry[1]
                )
            );
        }
        return $markdownContent;
    }
}
