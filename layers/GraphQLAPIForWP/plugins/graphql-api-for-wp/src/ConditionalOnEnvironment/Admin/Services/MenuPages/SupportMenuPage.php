<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnEnvironment\Admin\Services\MenuPages;

use InvalidArgumentException;
use GraphQLAPI\GraphQLAPI\ContentProcessors\ContentParserOptions;
use GraphQLAPI\GraphQLAPI\Facades\ContentProcessors\MarkdownContentParserFacade;

/**
 * Support menu page
 */
class SupportMenuPage extends AbstractDocsMenuPage
{
    use OpenInModalTriggerMenuPageTrait;

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
        $markdownContentParser = MarkdownContentParserFacade::getInstance();
        try {
            return $markdownContentParser->getContent('support.md', '', [ContentParserOptions::TAB_CONTENT => false]);
        } catch (InvalidArgumentException $e) {
            return sprintf(
                '<p>%s</p>',
                \__('Oops, there was a problem loading the page', 'graphql-api')
            );
        }
    }
}
