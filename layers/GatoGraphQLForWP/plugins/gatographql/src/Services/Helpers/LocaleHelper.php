<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\Helpers;

use GatoGraphQL\GatoGraphQL\PluginApp;
use GatoGraphQL\GatoGraphQL\StaticHelpers\LocaleUtils;

class LocaleHelper
{
    /**
     * @var string[]|null
     */
    private ?array $websiteTranslatedLanguages = null;

    /**
     * User's selected language code for the admin panel
     */
    public function getLocaleLanguage(): string
    {
        return LocaleUtils::getLocaleLanguage();
    }

    /**
     * Language codes for which a translated version of the website exists. The
     * website is localized to the same set of languages as the plugin itself, so
     * this is derived from the shipped translation files (gatographql-<locale>.l10n.php)
     * — staying in sync automatically as new locales are added.
     *
     * @return string[]
     */
    public function getWebsiteTranslatedLanguages(): array
    {
        if ($this->websiteTranslatedLanguages !== null) {
            return $this->websiteTranslatedLanguages;
        }
        $languages = [];
        $mainPlugin = PluginApp::getMainPlugin();
        // The .l10n.php files are named "<plugin-file-basename>-<locale>.l10n.php" (matching
        // the text-domain loading wiring in the plugin entry file).
        $prefix = basename($mainPlugin->getPluginFile(), '.php') . '-';
        $languagesDir = $mainPlugin->getPluginDir() . '/languages/';
        foreach (glob($languagesDir . $prefix . '*.l10n.php') ?: [] as $translationFile) {
            // "gatographql-es_ES.l10n.php" => locale "es_ES" => language "es"
            $locale = substr(basename($translationFile), strlen($prefix), -strlen('.l10n.php'));
            $language = explode('_', $locale)[0];
            $languages[$language] = $language;
        }
        $this->websiteTranslatedLanguages = array_values($languages);
        return $this->websiteTranslatedLanguages;
    }
}
