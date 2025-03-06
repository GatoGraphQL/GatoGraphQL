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
    public const ELEMENTOR = Plugin::NAMESPACE . '\\bundle-extensions\\elementor';
    public const EVENTS_MANAGER = Plugin::NAMESPACE . '\\bundle-extensions\\events-manager';
    public const MULTILINGUALPRESS = Plugin::NAMESPACE . '\\bundle-extensions\\multilingualpress';
    public const POLYLANG = Plugin::NAMESPACE . '\\bundle-extensions\\polylang';
    public const TRANSLATION = Plugin::NAMESPACE . '\\bundle-extensions\\translation';

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
            self::ELEMENTOR,
            self::EVENTS_MANAGER,
            self::MULTILINGUALPRESS,
            self::POLYLANG,
            self::TRANSLATION,
        ];
    }

    public function getName(string $module): string
    {
        return match ($module) {
            self::AUTOMATION => \__('Automation', 'gatographql'),
            self::ELEMENTOR => \__('Elementor', 'gatographql'),
            self::EVENTS_MANAGER => \__('Events Manager', 'gatographql'),
            self::MULTILINGUALPRESS => \__('MultilingualPress', 'gatographql'),
            self::POLYLANG => \__('Polylang', 'gatographql'),
            self::TRANSLATION => \__('Translation', 'gatographql'),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        return match ($module) {
            self::AUTOMATION => \__('Use GraphQL to automate tasks in your app: Execute queries when some event happens, chain queries, and schedule and trigger queries via WP-Cron. (The Internal GraphQL Server extension is required).', 'gatographql'),
            self::ELEMENTOR => \__('Integration with plugin "Elementor", adding fields to parse and update data in Elementor pages and templates.', 'gatographql'),
            self::EVENTS_MANAGER => \__('Integration with plugin "Events Manager", adding fields to the schema to fetch event data.', 'gatographql'),
            self::MULTILINGUALPRESS => \__('Integration with plugin "MultilingualPress", adding fields to the schema to fetch multilingual data.', 'gatographql'),
            self::POLYLANG => \__('Integration with plugin "Polylang", adding fields to the schema to fetch multilingual data.', 'gatographql'),
            self::TRANSLATION => \__('Translate content to multiple languages using the service provider of your choice, among ChatGPT, Claude, DeepSeek, Mistral AI, DeepL, and Google Translate.', 'gatographql'),
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
            self::ELEMENTOR => $imagePathURL . '/elementor.svg',
            self::EVENTS_MANAGER => $imagePathURL . '/events-manager.webp',
            self::MULTILINGUALPRESS => $imagePathURL . '/multilingualpress.webp',
            self::POLYLANG => $imagePathURL . '/polylang.webp',
            self::TRANSLATION => $imagePathURL . '/translation.svg',
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
            self::ELEMENTOR => [
                PremiumExtensionModuleResolver::ELEMENTOR,
            ],
            self::EVENTS_MANAGER => [
                PremiumExtensionModuleResolver::EVENTS_MANAGER,
            ],
            self::MULTILINGUALPRESS => [
                PremiumExtensionModuleResolver::MULTILINGUALPRESS,
            ],
            self::POLYLANG => [
                PremiumExtensionModuleResolver::POLYLANG,
            ],
            self::TRANSLATION => [
                PremiumExtensionModuleResolver::CHATGPT_TRANSLATION,
                PremiumExtensionModuleResolver::CLAUDE_TRANSLATION,
                PremiumExtensionModuleResolver::DEEPL,
                PremiumExtensionModuleResolver::DEEPSEEK_TRANSLATION,
                PremiumExtensionModuleResolver::GOOGLE_TRANSLATE,
                PremiumExtensionModuleResolver::MISTRALAI_TRANSLATION,
                PremiumExtensionModuleResolver::TRANSLATION,
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
