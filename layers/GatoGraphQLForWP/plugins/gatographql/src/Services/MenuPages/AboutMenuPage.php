<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\ContentProcessors\PluginMarkdownContentRetrieverTrait;
use GatoGraphQL\GatoGraphQL\PluginApp;
use GatoGraphQL\GatoGraphQL\StaticHelpers\SettingsHelpers;
use PoP\ComponentModel\Misc\GeneralUtils;

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

    public function getMenuPageTitle(): string
    {
        return __('About', 'gatographql');
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
        if (!parent::isCurrentScreen()) {
            return false;
        }
        return !$this->getMenuPageHelper()->isDocumentationScreen();
    }

    protected function getContentToPrint(): string
    {
        $content = $this->getPageMarkdownContent();
        if ($content === null) {
            return sprintf(
                '<p>%s</p>',
                __('Oops, there was a problem loading the page', 'gatographql')
            );
        }

        /**
         * Inject the translatable prose (so the page is localized via the plugin's
         * .mo) and the dynamic values (plugin name, URLs) into the rendered HTML.
         * This always runs, so the page is correct for every user.
         */
        $replacements = $this->getMarkdownPlaceholderReplacements();

        /**
         * The customer-only support form (name/email prefill + the attached
         * "extension license data") is filled, and shown, only when a commercial
         * license is active. Generate that dynamic content and inject it too.
         */
        $commercialExtensionActivatedLicenseObjectProperties = SettingsHelpers::getCommercialExtensionActivatedLicenseObjectProperties();
        if ($commercialExtensionActivatedLicenseObjectProperties !== []) {
            $customerName = '';
            $customerEmail = '';
            $extensionLicenseItems = [];
            foreach ($commercialExtensionActivatedLicenseObjectProperties as $extensionCommercialExtensionActivatedLicenseObjectProperties) {
                $extensionLicenseItems[] = implode(
                    PHP_EOL,
                    [
                        'License Key: ' . $extensionCommercialExtensionActivatedLicenseObjectProperties->licenseKey,
                        'Product: ' . $extensionCommercialExtensionActivatedLicenseObjectProperties->productName,
                        'Product ID: ' . ($extensionCommercialExtensionActivatedLicenseObjectProperties->productID ?? ''),
                        'Instance Name: ' . ($extensionCommercialExtensionActivatedLicenseObjectProperties->instanceName ?? ''),
                        'Instance ID: ' . ($extensionCommercialExtensionActivatedLicenseObjectProperties->instanceID ?? ''),
                        'Status: ' . $extensionCommercialExtensionActivatedLicenseObjectProperties->status,
                    ]
                );
                $customerName = $extensionCommercialExtensionActivatedLicenseObjectProperties->customerName;
                $customerEmail = $extensionCommercialExtensionActivatedLicenseObjectProperties->customerEmail;
            }
            $extensionsLicenseData = implode(
                PHP_EOL . PHP_EOL,
                [
                    'Domain: ' . GeneralUtils::getHost(home_url()),
                    implode(
                        PHP_EOL . PHP_EOL,
                        $extensionLicenseItems
                    ),
                ]
            );
            $textInputValueInjections = [
                'placeholder="John Doe"' => $customerName,
                'placeholder="your@email.com"' => $customerEmail,
            ];
            foreach ($textInputValueInjections as $search => $valueInject) {
                $replacements[$search] = sprintf(
                    '%s value="%s"',
                    $search,
                    $valueInject
                );
            }
            $textareaInputValueInjections = [
                '{extensions-license-data}' => $extensionsLicenseData,
            ];
            foreach ($textareaInputValueInjections as $search => $valueInject) {
                $replacements[$search . '</textarea>'] = sprintf(
                    '%s</textarea>',
                    $valueInject
                );
            }
        }

        $content = str_replace(array_keys($replacements), array_values($replacements), $content);

        return sprintf('<div class="wrap" markdown=1>%s</div>', $content);
    }

    /**
     * Map of Markdown placeholder tokens to their (translated) values. Routing the
     * About page prose through here means it is localized via the plugin's .mo,
     * instead of being hardcoded English in the Markdown file (which `make-pot`
     * cannot extract). Subclasses override entries to vary the page (e.g. title).
     *
     * @return array<string,string>
     */
    protected function getMarkdownPlaceholderReplacements(): array
    {
        $mainPlugin = PluginApp::getMainPlugin();
        $pluginName = $mainPlugin->getPluginName();
        return [
            '{title}' => sprintf(__('About %s', 'gatographql'), $pluginName),
            '{tagline}' => __('Powerful and flexible GraphQL server for WordPress.', 'gatographql'),
            '{our-channels-title}' => __('Our channels', 'gatographql'),
            '{website-label}' => __('Website:', 'gatographql'),
            '{source-code-label}' => __('Source code:', 'gatographql'),
            '{youtube-label}' => __('YouTube channel:', 'gatographql'),
            '{contact-label}' => __('Contact:', 'gatographql'),
            '{newsletter-intro}' => sprintf(__('<strong>Subscribe to our newsletter</strong> to receive timely updates about %s:', 'gatographql'), $pluginName),
            '{name-label}' => __('Name:', 'gatographql'),
            '{email-label}' => __('Email:', 'gatographql'),
            '{subscribe-button}' => __('Subscribe', 'gatographql'),
            '{release-notes-title}' => __('Release Notes', 'gatographql'),
            '{release-notes-intro}' => __('New features released on each version:', 'gatographql'),
            '{current}' => __('current', 'gatographql'),
            '{support-title}' => __('Support', 'gatographql'),
            '{support-customers-note}' => __('Support is provided to customers of any Gato GraphQL product with an active license.', 'gatographql'),
            '{support-intro}' => __('Send your message to the Gato GraphQL Support team:', 'gatographql'),
            '{your-name-label}' => __('Your name:', 'gatographql'),
            '{your-email-label}' => __('Your email:', 'gatographql'),
            '{subject-label}' => __('Subject:', 'gatographql'),
            '{message-label}' => __('Message:', 'gatographql'),
            '{send-message-button}' => __('Send message', 'gatographql'),
            '{license-data-label}' => __('Attached extension license data', 'gatographql'),
            '{plugin-name}' => $pluginName,
            '{contact-form-url}' => $mainPlugin->getPluginDomainURL() . '/__forms/support.html',
        ];
    }

    protected function getPageMarkdownContent(): ?string
    {
        return $this->getMarkdownContent(
            'about',
            'general',
            $this->getMarkdownContentOptions()
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
            'gatographql-about',
            $mainPluginURL . 'assets/css/about.css',
            array(),
            $mainPluginVersion
        );
    }
}
