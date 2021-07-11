<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\MenuPages;

use GraphQLAPI\GraphQLAPI\ContentProcessors\MarkdownContentRetrieverTrait;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\AbstractPluginMenuPage;

/**
 * Docs menu page
 */
abstract class AbstractDocsMenuPage extends AbstractPluginMenuPage
{
    use OpenInModalMenuPageTrait;
    use UseTabpanelMenuPageTrait;
    use MarkdownContentRetrieverTrait;

    public function print(): void
    {
        ?>
        <div
            class="<?php echo implode(' ', $this->getDivClasses()) ?>"
        >
            <?php echo $this->getContentToPrint() ?>
        </div>
        <?php
    }

    /**
     * Classes to add to the output <div>
     *
     * @return string[]
     */
    protected function getDivClasses(): array
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

    /**
     * Enqueue the required assets and initialize the localized scripts
     */
    protected function enqueueAssets(): void
    {
        parent::enqueueAssets();

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
    }
}
