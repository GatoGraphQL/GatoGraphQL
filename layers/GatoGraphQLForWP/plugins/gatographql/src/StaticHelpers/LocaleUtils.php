<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\StaticHelpers;

use GatoGraphQL\GatoGraphQL\PluginApp;

/**
 * "Static" because it can be invoked from a TypeModuleResolver's getSettingsDefaultValue
 * and printed on the Settings page, where the services have not yet been initialized
 */
class LocaleUtils
{
    /**
     * @var string[]|null
     */
    private static ?array $websiteTranslatedLanguages = null;

    /**
     * User's selected language code for the admin panel
     */
    public static function getLocaleLanguage(): string
    {
        // locale has shape "en_US". Retrieve the language code only: "en"
        $localeParts = explode('_', \get_locale());
        return $localeParts[0];
    }

    /**
     * Language codes for which a translated version of the website exists. The
     * website is localized to the same set of languages as the plugin itself, so
     * this is derived from the shipped translation files (gatographql-<locale>.mo)
     * — staying in sync automatically as new locales are added.
     *
     * @return string[]
     */
    public static function getWebsiteTranslatedLanguages(): array
    {
        if (self::$websiteTranslatedLanguages !== null) {
            return self::$websiteTranslatedLanguages;
        }
        $languages = [];
        $mainPlugin = PluginApp::getMainPlugin();
        // The .mo files are named "<plugin-file-basename>-<locale>.mo" (matching the
        // text-domain loading wiring in the plugin entry file).
        $prefix = basename($mainPlugin->getPluginFile(), '.php') . '-';
        $languagesDir = $mainPlugin->getPluginDir() . '/languages/';
        foreach (glob($languagesDir . $prefix . '*.mo') ?: [] as $moFile) {
            // "gatographql-es_ES.mo" => locale "es_ES" => language "es"
            $locale = substr(basename($moFile), strlen($prefix), -strlen('.mo'));
            $language = explode('_', $locale)[0];
            $languages[$language] = $language;
        }
        self::$websiteTranslatedLanguages = array_values($languages);
        return self::$websiteTranslatedLanguages;
    }
}
