<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\ContentProcessors\ContentParserOptions;
use GatoGraphQL\GatoGraphQL\ContentProcessors\PluginMarkdownContentRetrieverTrait;
use GatoGraphQL\GatoGraphQL\Marketplace\Constants\LicenseProperties;
use GatoGraphQL\GatoGraphQL\PluginApp;
use GatoGraphQL\GatoGraphQL\Settings\Options;
use PoP\ComponentModel\Misc\GeneralUtils;

use function get_option;

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
        $content = $this->getMarkdownContent(
            'about',
            'general',
            [
                ContentParserOptions::TAB_CONTENT => $this->useTabpanelForContent(),
            ]
        );

        if ($content === null) {
            return sprintf(
                '<p>%s</p>',
                \__('Oops, there was a problem loading the page', 'gato-graphql')
            );
        }

        $extensionLicenseItems = [];
        /** @var array<string,mixed> */
        $commercialExtensionActivatedLicenseEntries = get_option(Options::COMMERCIAL_EXTENSION_ACTIVATED_LICENSE_ENTRIES, []);
        foreach ($commercialExtensionActivatedLicenseEntries as $extensionSlug => $commercialExtensionActivatedLicenseEntry) {
            $extensionLicenseItems[] = sprintf(
                '%s%s%s%s%s%s%s',
                'Extension: ' . $commercialExtensionActivatedLicenseEntry[LicenseProperties::PRODUCT_NAME],
                PHP_EOL,
                'Instance Name: ' . $commercialExtensionActivatedLicenseEntry[LicenseProperties::INSTANCE_NAME],
                PHP_EOL,
                'Instance ID: ' . $commercialExtensionActivatedLicenseEntry[LicenseProperties::INSTANCE_ID],
                PHP_EOL,
                'Status: ' . $commercialExtensionActivatedLicenseEntry[LicenseProperties::STATUS],
            );
        }
        $extensionsLicenseData = sprintf(
            '%s%s%s',
            'Domain: ' . GeneralUtils::getHost(\home_url()),
            PHP_EOL . PHP_EOL,
            implode(
                PHP_EOL . PHP_EOL,
                $extensionLicenseItems
            )
        );

        /**
         * Input dynamic content into the form in the generated HTML
         */
        $replacements = [];
        $textInputValueInjections = [
            'placeholder="pedro@yahoo.com"' => \get_option('admin_email', ''),
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
        $content = str_replace(array_keys($replacements), array_values($replacements), $content);

        return $content;
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
