<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\ContentProcessors\ContentParserOptions;
use GatoGraphQL\GatoGraphQL\ContentProcessors\PluginMarkdownContentRetrieverTrait;
use GatoGraphQL\GatoGraphQL\PluginApp;

/**
 * About menu page
 */
class AboutMenuPage extends AbstractDocsMenuPage
{
    use OpenInModalTriggerMenuPageTrait;
    use PluginMarkdownContentRetrieverTrait;

    public function getMenuPageSlug(): string
    {
        return 'about';
    }

    protected function useTabpanelForContent(): bool
    {
        return true;
    }

    /**
     * Validate the param also
     */
    protected function isCurrentScreen(): bool
    {
        return !$this->getMenuPageHelper()->isDocumentationScreen() && parent::isCurrentScreen();
    }

    protected function getContentToPrint(): string
    {
        return $this->getMarkdownContent(
            'about',
            'general',
            [
                ContentParserOptions::TAB_CONTENT => $this->useTabpanelForContent(),
            ]
        ) ?? sprintf(
            '<p>%s</p>',
            \__('Oops, there was a problem loading the page', 'gato-graphql')
        );
    }

    /**
     * Enqueue the required assets
     */
    protected function enqueueAssets(): void
    {
        parent::enqueueAssets();

        $this->enqueueModalTriggerAssets();

        $this->enqueueAboutPageAssets();
    }
    
    protected function enqueueAboutPageAssets(): void
    {
        $mainPlugin = PluginApp::getMainPlugin();
        $mainPluginURL = $mainPlugin->getPluginURL();
        $mainPluginVersion = $mainPlugin->getPluginVersion();

        \wp_enqueue_style(
            'gato-graphql-about',
            $mainPluginURL . 'assets/css/about.css',
            array(),
            $mainPluginVersion
        );
    }
}
