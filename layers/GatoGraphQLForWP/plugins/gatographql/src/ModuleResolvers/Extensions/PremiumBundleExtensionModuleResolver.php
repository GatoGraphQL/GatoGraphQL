<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions;

use GatoGraphQL\GatoGraphQL\Plugin;
use GatoGraphQL\GatoGraphQL\PluginApp;
use GatoGraphQL\GatoGraphQL\PluginStaticModuleConfiguration;

class PremiumBundleExtensionModuleResolver extends AbstractBundleExtensionModuleResolver
{
    use PremiumExtensionModuleResolverTrait;

    public const AUTOMATION = Plugin::NAMESPACE . '\\bundle-extensions\\automation';
    public const DEEPL = Plugin::NAMESPACE . '\\bundle-extensions\\deepl';
    public const EVENTS_MANAGER = Plugin::NAMESPACE . '\\bundle-extensions\\events-manager';
    public const GOOGLE_TRANSLATE = Plugin::NAMESPACE . '\\bundle-extensions\\google-translate';
    public const MULTILINGUALPRESS = Plugin::NAMESPACE . '\\bundle-extensions\\multilingualpress';
    public const POLYLANG = Plugin::NAMESPACE . '\\bundle-extensions\\polylang';

    /**
     * @return string[]
     */
    public function getModulesToResolve(): array
    {
        if (!PluginStaticModuleConfiguration::displayGatoGraphQLPROFeatureBundlesOnExtensionsPage()) {
            return [];
        }
        return [
            self::AUTOMATION,
            self::DEEPL,
            self::EVENTS_MANAGER,
            self::GOOGLE_TRANSLATE,
            self::MULTILINGUALPRESS,
            self::POLYLANG,
        ];
    }

    public function getName(string $module): string
    {
        return match ($module) {
            self::AUTOMATION => \__('Automation', 'gatographql'),
            self::DEEPL => \__('DeepL', 'gatographql'),
            self::EVENTS_MANAGER => \__('Events Manager', 'gatographql'),
            self::GOOGLE_TRANSLATE => \__('Google Translate', 'gatographql'),
            self::MULTILINGUALPRESS => \__('MultilingualPress', 'gatographql'),
            self::POLYLANG => \__('Polylang', 'gatographql'),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        return match ($module) {
            self::AUTOMATION => \__('Use GraphQL to automate tasks in your app: Execute queries when some event happens, chain queries, and schedule and trigger queries via WP-Cron. (The Internal GraphQL Server extension is required).', 'gatographql'),
            self::DEEPL => \__('Translate content to multiple languages using the DeepL API.', 'gatographql'),
            self::EVENTS_MANAGER => \__('Integration with plugin "Events Manager", adding fields to the schema to fetch event data.', 'gatographql'),
            self::GOOGLE_TRANSLATE => \__('Translate content to multiple languages using the Google Translate API.', 'gatographql'),
            self::MULTILINGUALPRESS => \__('Integration with plugin "MultilingualPress", adding fields to the schema to fetch multilingual data.', 'gatographql'),
            self::POLYLANG => \__('Integration with plugin "Polylang", adding fields to the schema to fetch multilingual data.', 'gatographql'),
            default => parent::getDescription($module),
        };
    }

    public function getPriority(): int
    {
        return 20;
    }

    public function getLogoURL(string $module): string
    {
        $pluginURL = PluginApp::getMainPlugin()->getPluginURL();
        $imagePathURL = $pluginURL . 'assets/img/extension-logos';
        return match ($module) {
            self::AUTOMATION => $imagePathURL . '/automation.svg',
            self::DEEPL => $imagePathURL . '/deepl.svg',
            self::EVENTS_MANAGER => $imagePathURL . '/events-manager.webp',
            self::GOOGLE_TRANSLATE => $imagePathURL . '/google-translate.svg',
            self::MULTILINGUALPRESS => $imagePathURL . '/multilingualpress.png',
            self::POLYLANG => $imagePathURL . '/polylang.png',
            default => parent::getLogoURL($module),
        };
    }

    /**
     * @return string[]
     */
    public function getBundledExtensionModules(string $module): array
    {
        return match ($module) {
            self::AUTOMATION => [
                PremiumExtensionModuleResolver::AUTOMATION,
            ],
            self::DEEPL => [
                PremiumExtensionModuleResolver::DEEPL,
                PremiumExtensionModuleResolver::TRANSLATION,
            ],
            self::EVENTS_MANAGER => [
                PremiumExtensionModuleResolver::EVENTS_MANAGER,
            ],
            self::GOOGLE_TRANSLATE => [
                PremiumExtensionModuleResolver::GOOGLE_TRANSLATE,
                PremiumExtensionModuleResolver::TRANSLATION,
            ],
            self::MULTILINGUALPRESS => [
                PremiumExtensionModuleResolver::MULTILINGUALPRESS,
            ],
            self::POLYLANG => [
                PremiumExtensionModuleResolver::POLYLANG,
            ],
            default => [],
        };
    }

    /**
     * @return string[]
     */
    public function getBundledBundleExtensionModules(string $module): array
    {
        return [];
    }
}
