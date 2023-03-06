<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\MenuPages;

use GraphQLAPI\GraphQLAPI\ContentProcessors\ContentParserOptions;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\AbstractDocsMenuPage;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\OpenInModalTriggerMenuPageTrait;
use GraphQLAPI\GraphQLAPIPRO\ContentProcessors\PROPluginExtensionMarkdownContentRetrieverTrait;

class RecipesMenuPage extends AbstractDocsMenuPage
{
    use OpenInModalTriggerMenuPageTrait;
    use PROPluginExtensionMarkdownContentRetrieverTrait;

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
        return $this->getMarkdownContent(
            'general/recipes',
            '',
            [
                ContentParserOptions::TAB_CONTENT => true,
            ]
        ) ?? sprintf(
            '<p>%s</p>',
            \__('Oops, there was a problem loading the page', 'graphql-api')
        );
    }
}
