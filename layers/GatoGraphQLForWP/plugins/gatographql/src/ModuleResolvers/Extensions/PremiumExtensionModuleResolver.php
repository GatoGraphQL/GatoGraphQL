<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions;

use GatoGraphQL\GatoGraphQL\Plugin;

class PremiumExtensionModuleResolver extends AbstractExtensionModuleResolver
{
    use PremiumExtensionModuleResolverTrait;

    public const AUTOMATION = Plugin::NAMESPACE . '\\extensions\\automation';
    public const BRICKS = Plugin::NAMESPACE . '\\extensions\\bricks';
    public const CHATGPT_TRANSLATION = Plugin::NAMESPACE . '\\extensions\\chatgpt-translation';
    public const CLAUDE_TRANSLATION = Plugin::NAMESPACE . '\\extensions\\claude-translation';
    public const DEEPL = Plugin::NAMESPACE . '\\extensions\\deepl';
    public const DEEPSEEK_TRANSLATION = Plugin::NAMESPACE . '\\extensions\\deepseek-translation';
    public const ELEMENTOR = Plugin::NAMESPACE . '\\extensions\\elementor';
    public const EVENTS_MANAGER = Plugin::NAMESPACE . '\\extensions\\events-manager';
    public const GOOGLE_TRANSLATE = Plugin::NAMESPACE . '\\extensions\\google-translate';
    public const MISTRALAI_TRANSLATION = Plugin::NAMESPACE . '\\extensions\\mistralai-translation';
    public const MULTILINGUALPRESS = Plugin::NAMESPACE . '\\extensions\\multilingualpress';
    public const OPENROUTER_TRANSLATION = Plugin::NAMESPACE . '\\extensions\\openrouter-translation';
    public const POLYLANG = Plugin::NAMESPACE . '\\extensions\\polylang';
    public const TRANSLATION = Plugin::NAMESPACE . '\\extensions\\translation';
    public const WOOCOMMERCE = Plugin::NAMESPACE . '\\extensions\\woocommerce';

    /**
     * @return string[]
     */
    public function getModulesToResolve(): array
    {
        return [
            self::AUTOMATION,
            self::BRICKS,
            self::CHATGPT_TRANSLATION,
            self::CLAUDE_TRANSLATION,
            self::DEEPL,
            self::DEEPSEEK_TRANSLATION,
            self::ELEMENTOR,
            self::EVENTS_MANAGER,
            self::GOOGLE_TRANSLATE,
            self::MISTRALAI_TRANSLATION,
            self::MULTILINGUALPRESS,
            self::OPENROUTER_TRANSLATION,
            self::POLYLANG,
            self::TRANSLATION,
            self::WOOCOMMERCE,
        ];
    }

    public function getName(string $module): string
    {
        return match ($module) {
            self::AUTOMATION => \__('Automation', 'gatographql'),
            self::BRICKS => \__('Bricks', 'gatographql'),
            self::CHATGPT_TRANSLATION => \__('ChatGPT Translation', 'gatographql'),
            self::CLAUDE_TRANSLATION => \__('Claude Translation', 'gatographql'),
            self::DEEPL => \__('DeepL', 'gatographql'),
            self::DEEPSEEK_TRANSLATION => \__('DeepSeek', 'gatographql'),
            self::ELEMENTOR => \__('Elementor', 'gatographql'),
            self::EVENTS_MANAGER => \__('Events Manager', 'gatographql'),
            self::GOOGLE_TRANSLATE => \__('Google Translate', 'gatographql'),
            self::MISTRALAI_TRANSLATION => \__('Mistral AI Translation', 'gatographql'),
            self::MULTILINGUALPRESS => \__('MultilingualPress', 'gatographql'),
            self::OPENROUTER_TRANSLATION => \__('OpenRouter Translation', 'gatographql'),
            self::POLYLANG => \__('Polylang', 'gatographql'),
            self::TRANSLATION => \__('Translation', 'gatographql'),
            self::WOOCOMMERCE => \__('WooCommerce', 'gatographql'),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        return match ($module) {
            self::AUTOMATION => \__('Use GraphQL to automate tasks in your app: Execute queries when some event happens, chain queries, and schedule and trigger queries via WP-Cron. (The Internal GraphQL Server extension is required).', 'gatographql'),
            self::BRICKS => \__('Integration with plugin "Bricks", adding fields to parse and update data in Bricks pages and templates.', 'gatographql'),
            self::CHATGPT_TRANSLATION => \__('Translate content to multiple languages using the ChatGPT API.', 'gatographql'),
            self::CLAUDE_TRANSLATION => \__('Translate content to multiple languages using the Claude API.', 'gatographql'),
            self::DEEPL => \__('Translate content to multiple languages using the DeepL API.', 'gatographql'),
            self::DEEPSEEK_TRANSLATION => \__('Translate content to multiple languages using the DeepSeek API.', 'gatographql'),
            self::ELEMENTOR => \__('Integration with plugin "Elementor", adding fields to parse and update data in Elementor pages and templates.', 'gatographql'),
            self::EVENTS_MANAGER => \__('Integration with plugin "Events Manager", adding fields to the schema to fetch event data.', 'gatographql'),
            self::GOOGLE_TRANSLATE => \__('Translate content to multiple languages using the Google Translate API.', 'gatographql'),
            self::MISTRALAI_TRANSLATION => \__('Translate content to multiple languages using the Mistral AI API.', 'gatographql'),
            self::MULTILINGUALPRESS => \__('Integration with plugin "MultilingualPress", adding fields to the schema to fetch multilingual data.', 'gatographql'),
            self::OPENROUTER_TRANSLATION => \__('Translate content to multiple languages using the OpenRouter API.', 'gatographql'),
            self::POLYLANG => \__('Integration with plugin "Polylang", adding fields to the schema to fetch multilingual data.', 'gatographql'),
            self::TRANSLATION => \__('Translate content to multiple languages using any provider\'s API.', 'gatographql'),
            self::WOOCOMMERCE => \__('Integration with WooCommerce, to fetch product data.', 'gatographql'),
            default => parent::getDescription($module),
        };
    }
}
