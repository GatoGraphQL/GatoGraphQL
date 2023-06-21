<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions;

use GatoGraphQL\GatoGraphQL\Plugin;
use GatoGraphQL\GatoGraphQL\PluginApp;

class BundleExtensionModuleResolver extends AbstractBundleExtensionModuleResolver
{
    public const ALL_EXTENSIONS = Plugin::NAMESPACE . '\\bundle-extensions\\all-extensions';

    /**
     * @return string[]
     */
    public function getModulesToResolve(): array
    {
        return [
            self::ALL_EXTENSIONS,
        ];
    }

    public function getName(string $module): string
    {
        return match ($module) {
            self::ALL_EXTENSIONS => \__('"All Extensions" Bundle', 'gato-graphql'),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        return match ($module) {
            self::ALL_EXTENSIONS => \__('When being a member of the Gato GraphQL Club, you have access to all the extensions (from now and the future), via a single bundle.', 'gato-graphql'),
            default => parent::getDescription($module),
        };
    }

    public function getPriority(): int
    {
        return 20;
    }

    public function getLogoURL(string $module): string
    {
        return match ($module) {
            self::ALL_EXTENSIONS => PluginApp::getMainPlugin()->getPluginURL() . 'assets/img/logos/GatoGraphQL-logo-face.png',
            default => parent::getLogoURL($module),
        };
    }

    /**
     * @return string[]
     */
    public function getBundledExtensionSlugs(string $module): array
    {
        return match ($module) {
            self::ALL_EXTENSIONS =>         [
                'access-control',
                'access-control-visitor-ip',
                'automation',
                'cache-control',
                'conditional-field-manipulation',
                'deprecation-notifier',
                'email-sender',
                'events-manager',
                'field-default-value',
                'field-deprecation',
                'field-on-field',
                'field-resolution-caching',
                'field-response-removal',
                'field-to-input',
                'field-value-iteration-and-manipulation',
                'google-translate',
                'helper-function-collection',
                'http-client',
                'http-request-via-schema',
                'internal-graphql-server',
                'low-level-persisted-query-editing',
                'multiple-query-execution',
                'php-constants-and-environment-variables-via-schema',
                'php-functions-via-schema',
                'response-error-trigger',
                'schema-editing-access',
            ],
            default => [],
        };
    }
}
