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
                \__('Oops, there was a problem loading the page', 'gatographql')
            );
        }

        /**
         * Input dynamic content into the form in the generated HTML.
         * Generate the "Attached extension license data", and extract
         * an instance of customer name/email for those fields.
         */
        $commercialExtensionActivatedLicenseObjectProperties = SettingsHelpers::getCommercialExtensionActivatedLicenseObjectProperties();
        if ($commercialExtensionActivatedLicenseObjectProperties === []) {
            return $content;
        }

        $customerName = '';
        $customerEmail = '';
        $extensionLicenseItems = [];
        foreach ($commercialExtensionActivatedLicenseObjectProperties as $extensionCommercialExtensionActivatedLicenseObjectProperties) {
            $extensionLicenseItems[] = implode(
                PHP_EOL,
                [
                    'License Key: ' . $extensionCommercialExtensionActivatedLicenseObjectProperties->licenseKey,
                    'Product: ' . $extensionCommercialExtensionActivatedLicenseObjectProperties->productName,
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
                'Domain: ' . GeneralUtils::getHost(\home_url()),
                implode(
                    PHP_EOL . PHP_EOL,
                    $extensionLicenseItems
                ),
            ]
        );

        $replacements = [];
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
        $variableValueInjections = [
            '{extension-website}' => PluginApp::getMainPlugin()->getPluginWebsiteURL(),
        ];
        foreach ($variableValueInjections as $search => $valueInject) {
            $replacements[$search] = sprintf(
                '%s',
                $valueInject
            );
        }
        $content = str_replace(array_keys($replacements), array_values($replacements), $content);

        return sprintf('<div class="wrap" markdown=1>%s</div>', $content);
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
