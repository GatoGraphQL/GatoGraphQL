<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\MenuPages;

use GraphQLAPI\GraphQLAPI\ContentProcessors\ContentParserOptions;
use GraphQLAPI\GraphQLAPI\ContentProcessors\PluginMarkdownContentRetrieverTrait;

/**
 * Support menu page
 */
class SupportMenuPage extends AbstractDocsMenuPage
{
    use OpenInModalTriggerMenuPageTrait;
    use PluginMarkdownContentRetrieverTrait;

    public function getMenuPageSlug(): string
    {
        return 'support';
    }

    protected function useTabpanelForContent(): bool
    {
        return false;
    }

    protected function getContentToPrint(): string
    {
        return $this->getMarkdownContent(
            'support.md',
            '',
            [
                ContentParserOptions::TAB_CONTENT => false,
            ],
            sprintf(
                '<p>%s</p>',
                \__('Oops, there was a problem loading the page', 'graphql-api')
            )
        );
    }
}
