<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\App;
use GatoGraphQL\GatoGraphQL\Constants\RequestParams;
use GatoGraphQL\GatoGraphQL\ContentProcessors\ContentParserOptions;
use GatoGraphQL\GatoGraphQL\ContentProcessors\PluginMarkdownContentRetrieverTrait;
use GatoGraphQL\GatoGraphQL\Services\MenuPages\AbstractDocsMenuPage;

abstract class AbstractVerticalTabDocsMenuPage extends AbstractDocsMenuPage
{
    use PluginMarkdownContentRetrieverTrait;

    protected function useTabpanelForContent(): bool
    {
        return false;
    }

    protected function getContentToPrint(): string
    {
        $entries = $this->getEntries();
        // By default, focus on the first item
        $activeEntryName = $entries[0][0];
        // If passing a tab, focus on that one, if the module exists
        $tab = App::query(RequestParams::TAB);
        if ($tab !== null) {
            $entryNames = array_map(
                fn (array $entry) => $entry[0],
                $entries
            );
            if (in_array($tab, $entryNames)) {
                $activeEntryName = $tab;
            }
        }
        $class = 'wrap vertical-tabs gato-graphql-tabpanel';

        $markdownContent = sprintf(
            <<<HTML
            <div id="%s" class="%s">
                <h1>%s</h1>
                %s
                <div class="nav-tab-container">
                    <!-- Tabs -->
                    <h2 class="nav-tab-wrapper">
            HTML,
            $this->getContentID(),
            $class,
            $this->getPageTitle(),
            $this->getPageHeaderHTML()
        );

        // This page URL
        $url = admin_url(sprintf(
            'admin.php?page=%s',
            esc_attr(App::request('page') ?? App::query('page', ''))
        ));

        foreach ($entries as $i => $entry) {
            $entryName = $entry[0];
            $entryTitle = $entry[1];

            // Enumerate the entries
            $entryTitle = $this->enumerateEntries()
                ? sprintf(
                    \__('%s. %s', 'gato-graphql'),
                    $i + 1,
                    $entryTitle
                )
                : $entryTitle;

            /**
             * Also add the tab to the URL, not because it is needed,
             * but because we can then "Open in new tab" and it will
             * be focused already on that item.
             */
            $entryURL = sprintf(
                '%1$s&%2$s=%3$s',
                $url,
                RequestParams::TAB,
                $entryName
            );
            $entryID = $this->getEntryID($entryName);
            $markdownContent .= sprintf(
                '<a data-tab-target="%s" href="%s" class="nav-tab %s">%s</a>',
                '#' . $entryID,
                $entryURL,
                $entryName === $activeEntryName ? 'nav-tab-active' : '',
                $entryTitle
            );
        }

        $markdownContent .= <<<HTML
                    </h2>
                    <div class="nav-tab-content">
        HTML;

        $entryRelativePathDir = $this->getEntryRelativePathDir();
        foreach ($entries as $entry) {
            $entryName = $entry[0];
            $entryTitle = $entry[1];

            $entryContent = $this->getMarkdownContent(
                $entryName,
                $entryRelativePathDir,
                [
                    ContentParserOptions::TAB_CONTENT => false,
                ]
            ) ?? sprintf(
                '<p>%s</p>',
                sprintf(
                    \__('Oops, there was a problem loading entry "%s"', 'gato-graphql'),
                    $entryTitle
                )
            );

            // Hide the title from the content, as it's already shown below
            $entryContent = str_replace(
                '<h1>',
                '<h1 style="display: none;">',
                $entryContent
            );

            $entryID = $this->getEntryID($entryName);
            $markdownContent .= sprintf(
                <<<HTML
                    <div id="%s" class="%s" style="%s">
                        <h2>%s</h2><hr/>
                        %s
                    </div>
                HTML,
                $entryID,
                'tab-content',
                sprintf(
                    'display: %s;',
                    $entryName === $activeEntryName ? 'block' : 'none'
                ),
                $this->getEntryTitle(
                    $entryTitle,
                    $entry,
                ),
                $this->getEntryContent(
                    $entryContent,
                    $entry,
                )
            );
        }

        $markdownContent .= <<<HTML
                </div> <!-- class="nav-tab-content" -->
            </div> <!-- class="nav-tab-container" -->
        </div>
        HTML;
        return $markdownContent;
    }

    protected function getEntryID(string $entryName): string
    {
        return str_replace([':', ' ', '/', '.'], '_', $entryName);
    }

    protected function enumerateEntries(): bool
    {
        return false;
    }

    abstract protected function getPageTitle(): string;

    protected function getPageHeaderHTML(): string
    {
        return '';
    }

    protected function getContentID(): string
    {
        return 'gato-graphql-vertical-tab-docs';
    }

    abstract protected function getEntryRelativePathDir(): string;

    /**
     * @param array{0:string,1:string} $entry
     */
    protected function getEntryTitle(
        string $entryTitle,
        array $entry,
    ): string {
        return $entryTitle;
    }

    /**
     * @param array{0:string,1:string} $entry
     */
    protected function getEntryContent(
        string $entryContent,
        array $entry,
    ): string {
        return $entryContent;
    }

    /**
     * Enqueue the required assets and initialize the localized scripts
     */
    protected function enqueueAssets(): void
    {
        parent::enqueueAssets();

        $this->enqueueTabpanelAssets();
    }

    /**
     * @return array<array{0:string,1:string}>
     */
    abstract protected function getEntries(): array;
}
