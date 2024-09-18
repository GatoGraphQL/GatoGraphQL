<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\App;
use GatoGraphQL\GatoGraphQL\Constants\RequestParams;
use GatoGraphQL\GatoGraphQL\ContentProcessors\MarkdownContentParserInterface;
use GatoGraphQL\GatoGraphQL\ContentProcessors\MarkdownContentRetrieverTrait;

/**
 * Docs menu page
 */
abstract class AbstractDocsMenuPage extends AbstractPluginMenuPage
{
    use OpenInModalMenuPageTrait;
    use UseTabpanelMenuPageTrait;
    use UseCollapsibleContentMenuPageTrait;
    use PrettyprintCodePageTrait;
    use MarkdownContentRetrieverTrait;
    use UseDocsMenuPageTrait;
    use DocMenuPageTrait;

    private ?MarkdownContentParserInterface $markdownContentParser = null;

    final public function setMarkdownContentParser(MarkdownContentParserInterface $markdownContentParser): void
    {
        $this->markdownContentParser = $markdownContentParser;
    }
    final protected function getMarkdownContentParser(): MarkdownContentParserInterface
    {
        if ($this->markdownContentParser === null) {
            /** @var MarkdownContentParserInterface */
            $markdownContentParser = $this->instanceManager->getInstance(MarkdownContentParserInterface::class);
            $this->markdownContentParser = $markdownContentParser;
        }
        return $this->markdownContentParser;
    }

    public function print(): void
    {
        $content_safe = null;

        /**
         * If passing the doc via ?doc=... already print it.
         * Otherwise call method `getContentToPrint`
         */
        if (
            $this->getMenuPageHelper()->isDocumentationScreen()
            && App::query(RequestParams::DOC, '') !== ''
        ) {
            $content_safe = $this->getDocumentationContentToPrint();
        }

        $content_safe = $content_safe ?? $this->getContentToPrint();
        ?>
        <div
            class="<?php echo \esc_attr(implode(' ', $this->getDivClassNames())) ?>"
        >
            <?php echo $content_safe ?>
        </div>
        <?php
        echo $this->getAdditionalContentToPrint();
    }

    /**
     * Classes to add to the output <div>
     *
     * @return string[]
     */
    protected function getDivClassNames(): array
    {
        $classes = [];
        if ($this->openInModalWindow()) {
            $classes[] = 'modal-window-content-wrapper';
        }
        return $classes;
    }

    abstract protected function getContentToPrint(): string;

    protected function getAdditionalContentToPrint(): string
    {
        return '';
    }

    protected function openInModalWindow(): bool
    {
        return false;
    }

    protected function useTabpanelForContent(): bool
    {
        return false;
    }

    protected function highlightCode(): bool
    {
        return true;
    }

    protected function hasCollapsibleContent(): bool
    {
        return false;
    }

    /**
     * If the current page has been opened in a modal,
     * then do not further open links in yet a new modal.
     */
    protected function openMarkdownLinksInModal(): bool
    {
        return !$this->openInModalWindow();
    }

    /**
     * Enqueue the required assets and initialize the localized scripts
     */
    protected function enqueueAssets(): void
    {
        parent::enqueueAssets();

        $this->enqueueDocsAssets();

        /**
         * Add styles to open the page in a modal
         */
        if ($this->openInModalWindow()) {
            $this->enqueueModalAssets();
        }

        /**
         * Add styles/scripts to use a tabpanel
         */
        if ($this->useTabpanelForContent()) {
            $this->enqueueTabpanelAssets();
        }

        /**
         * Add styles/scripts to use a tabpanel
         */
        if ($this->highlightCode()) {
            $this->enqueueHighlightJSAssets();
        }

        /**
         * Add styles/scripts to use a tabpanel
         */
        if ($this->hasCollapsibleContent()) {
            $this->enqueueCollapsibleContentAssets();
        }
    }
}
