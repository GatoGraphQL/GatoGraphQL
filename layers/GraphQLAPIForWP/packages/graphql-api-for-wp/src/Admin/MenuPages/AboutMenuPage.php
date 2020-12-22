<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Admin\MenuPages;

use InvalidArgumentException;
use GraphQLAPI\GraphQLAPI\ContentProcessors\ContentParserOptions;
use GraphQLAPI\GraphQLAPI\Facades\ContentProcessors\MarkdownContentParserFacade;

/**
 * About menu page
 */
class AboutMenuPage extends AbstractDocsMenuPage
{
    use OpenInModalTriggerMenuPageTrait;

    public function getMenuPageSlug(): string
    {
        return 'about';
    }

    protected function useTabpanelForContent(): bool
    {
        return true;
    }

    protected function getContentToPrint(): string
    {
        $markdownContentParser = MarkdownContentParserFacade::getInstance();
        try {
            return $markdownContentParser->getContent('about.md', '', [ContentParserOptions::TAB_CONTENT => true]);
        } catch (InvalidArgumentException $e) {
            return sprintf(
                '<p>%s</p>',
                \__('Oops, there was a problem loading the page', 'graphql-api')
            );
        }
    }

    /**
     * Enqueue the required assets
     */
    protected function enqueueAssets(): void
    {
        parent::enqueueAssets();

        $this->enqueueModalTriggerAssets();
    }
}
