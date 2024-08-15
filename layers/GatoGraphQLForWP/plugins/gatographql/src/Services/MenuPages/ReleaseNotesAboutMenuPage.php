<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\ContentProcessors\PluginMarkdownContentRetrieverTrait;

/**
 * Release notes menu page
 */
class ReleaseNotesAboutMenuPage extends AbstractDocAboutMenuPage
{
    use PluginMarkdownContentRetrieverTrait;

    private ?AboutMenuPage $aboutMenuPage = null;

    final public function setAboutMenuPage(AboutMenuPage $aboutMenuPage): void
    {
        $this->aboutMenuPage = $aboutMenuPage;
    }
    final protected function getAboutMenuPage(): AboutMenuPage
    {
        if ($this->aboutMenuPage === null) {
            /** @var AboutMenuPage */
            $aboutMenuPage = $this->instanceManager->getInstance(AboutMenuPage::class);
            $this->aboutMenuPage = $aboutMenuPage;
        }
        return $this->aboutMenuPage;
    }

    public function getMenuPageSlug(): string
    {
        return $this->getAboutMenuPage()->getMenuPageSlug();
    }

    /**
     * Validate the param also
     */
    protected function isCurrentScreen(): bool
    {
        if (!parent::isCurrentScreen()) {
            return false;
        }
        return $this->getMenuPageHelper()->isDocumentationScreen();
    }

    /**
     * Any one document under release-notes. It doesn't matter
     * which one, as all links will start with "../" anyway
     */
    protected function getRelativePathDir(): string
    {
        $anyDocumentFolder = '0.9';
        return 'release-notes/' . $anyDocumentFolder;
    }
}
