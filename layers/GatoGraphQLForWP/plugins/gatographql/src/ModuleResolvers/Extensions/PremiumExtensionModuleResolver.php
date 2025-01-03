<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions;

use GatoGraphQL\GatoGraphQL\Plugin;

class PremiumExtensionModuleResolver extends AbstractExtensionModuleResolver
{
    use PremiumExtensionModuleResolverTrait;

    public const AUTOMATION = Plugin::NAMESPACE . '\\extensions\\automation';
    public const CHATGPT_TRANSLATION = Plugin::NAMESPACE . '\\extensions\\chatgpt-translation';
    public const DEEPL = Plugin::NAMESPACE . '\\extensions\\deepl';
    public const EVENTS_MANAGER = Plugin::NAMESPACE . '\\extensions\\events-manager';
    public const GOOGLE_TRANSLATE = Plugin::NAMESPACE . '\\extensions\\google-translate';
    public const MULTILINGUALPRESS = Plugin::NAMESPACE . '\\extensions\\multilingualpress';
    public const POLYLANG = Plugin::NAMESPACE . '\\extensions\\polylang';
    public const TRANSLATION = Plugin::NAMESPACE . '\\extensions\\translation';

    /**
     * @return string[]
     */
    public function getModulesToResolve(): array
    {
        return [
            self::AUTOMATION,
            self::CHATGPT_TRANSLATION,
            self::DEEPL,
            self::EVENTS_MANAGER,
            self::GOOGLE_TRANSLATE,
            self::MULTILINGUALPRESS,
            self::POLYLANG,
            self::TRANSLATION,
        ];
    }

    public function getName(string $module): string
    {
        return match ($module) {
            self::AUTOMATION => \__('Automation', 'gatographql'),
            self::CHATGPT_TRANSLATION => \__('ChatGPT Translation', 'gatographql'),
            self::DEEPL => \__('DeepL', 'gatographql'),
            self::EVENTS_MANAGER => \__('Events Manager', 'gatographql'),
            self::GOOGLE_TRANSLATE => \__('Google Translate', 'gatographql'),
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
            self::CHATGPT_TRANSLATION => \__('Translate content to multiple languages using the ChatGPT API.', 'gatographql'),
            self::DEEPL => \__('Translate content to multiple languages using the DeepL API.', 'gatographql'),
            self::EVENTS_MANAGER => \__('Integration with plugin "Events Manager", adding fields to the schema to fetch event data.', 'gatographql'),
            self::GOOGLE_TRANSLATE => \__('Translate content to multiple languages using the Google Translate API.', 'gatographql'),
            self::MULTILINGUALPRESS => \__('Integration with plugin "MultilingualPress", adding fields to the schema to fetch multilingual data.', 'gatographql'),
            self::POLYLANG => \__('Integration with plugin "Polylang", adding fields to the schema to fetch multilingual data.', 'gatographql'),
            self::TRANSLATION => \__('Translate content to multiple languages using any provider\'s API.', 'gatographql'),
            default => parent::getDescription($module),
        };
    }
}
