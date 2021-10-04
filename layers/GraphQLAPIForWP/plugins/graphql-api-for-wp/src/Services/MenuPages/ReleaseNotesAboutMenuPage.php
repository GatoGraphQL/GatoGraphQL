<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\MenuPages;

use GraphQLAPI\GraphQLAPI\ContentProcessors\PluginMarkdownContentRetrieverTrait;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * Release notes menu page
 */
class ReleaseNotesAboutMenuPage extends AbstractDocAboutMenuPage
{
    use PluginMarkdownContentRetrieverTrait;

    protected AboutMenuPage $aboutMenuPage;

    #[Required]
    final public function autowireReleaseNotesAboutMenuPage(
        AboutMenuPage $aboutMenuPage,
    ): void {
        $this->aboutMenuPage = $aboutMenuPage;
    }

    public function getMenuPageSlug(): string
    {
        return $this->aboutMenuPage->getMenuPageSlug();
    }

    /**
     * Validate the param also
     */
    protected function isCurrentScreen(): bool
    {
        return $this->menuPageHelper->isDocumentationScreen() && parent::isCurrentScreen();
    }

    protected function getRelativePathDir(): string
    {
        return 'release-notes';
    }
}
