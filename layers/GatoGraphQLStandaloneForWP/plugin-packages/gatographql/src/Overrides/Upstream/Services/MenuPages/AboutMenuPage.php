<?php

declare(strict_types=1);

namespace GatoGraphQLStandalone\GatoGraphQL\Overrides\Upstream\Services\MenuPages;

use GatoGraphQLStandalone\GatoGraphQL\ContentProcessors\StandalonePluginMarkdownContentRetrieverTrait;
use GatoGraphQL\GatoGraphQL\PluginApp;
use GatoGraphQL\GatoGraphQL\Services\MenuPages\AboutMenuPage as UpstreamAboutMenuPage;

class AboutMenuPage extends UpstreamAboutMenuPage
{
    use StandalonePluginMarkdownContentRetrieverTrait;

    /**
     * Only show the Support page when the user
     * has activated the product.
     */
    public function isServiceEnabled(): bool
    {
        $extensionManager = PluginApp::getExtensionManager();
        if (!$extensionManager->isExtensionLicenseActive(PluginApp::getMainPlugin()->getPluginSlug())) {
            return false;
        }
        return parent::isServiceEnabled();
    }

    public function getMenuPageSlug(): string
    {
        return 'support';
    }

    public function getMenuPageTitle(): string
    {
        return __('Support', 'gatographql');
    }

    protected function useTabpanelForContent(): bool
    {
        return false;
    }

    /**
     * This page is the "Support" page, so override the entries that differ from
     * the upstream About page (title, intro and the customers note).
     *
     * @return array<string,string>
     */
    protected function getMarkdownPlaceholderReplacements(): array
    {
        $pluginName = PluginApp::getMainPlugin()->getPluginName();
        return array_merge(
            parent::getMarkdownPlaceholderReplacements(),
            [
                '{title}' => sprintf(__('%s — Support', 'gatographql'), $pluginName),
                '{support-customers-note}' => sprintf(__('Support is provided to customers with an active license of %s.', 'gatographql'), $pluginName),
                '{support-intro}' => __('Send your message to our support team:', 'gatographql'),
            ]
        );
    }
}
