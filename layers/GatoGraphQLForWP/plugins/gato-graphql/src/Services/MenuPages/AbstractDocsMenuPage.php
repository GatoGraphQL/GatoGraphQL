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
        /** @var MarkdownContentParserInterface */
        return $this->markdownContentParser ??= $this->instanceManager->getInstance(MarkdownContentParserInterface::class);
    }

    public function print(): void
    {
        $content = null;

        /**
         * If passing the doc via ?doc=... already print it.
         * Otherwise call method `getContentToPrint`
         */
        if (
            $this->getMenuPageHelper()->isDocumentationScreen()
            && App::query(RequestParams::DOC, '') !== ''
        ) {
            $content = $this->getDocumentationContentToPrint();
        }
        ?>
        <div
            class="<?php echo implode(' ', $this->getDivClassNames()) ?>"
        >
            <?php echo $content ?? $this->getContentToPrint() ?>
        </div>
        <?php
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
    }
}
