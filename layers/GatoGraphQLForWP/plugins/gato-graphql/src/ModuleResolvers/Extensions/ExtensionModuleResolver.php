<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions;

use GatoGraphQL\GatoGraphQL\App;
use GatoGraphQL\GatoGraphQL\Module;
use GatoGraphQL\GatoGraphQL\ModuleConfiguration;
use GatoGraphQL\GatoGraphQL\Plugin;

class ExtensionModuleResolver extends AbstractExtensionModuleResolver
{
    public const GATO_GRAPHQL_PRO = Plugin::NAMESPACE . '\\extensions\\gato-graphql-pro';
    public const ACCESS_CONTROL = Plugin::NAMESPACE . '\\extensions\\access-control';
    public const ACCESS_CONTROL_VISITOR_IP = Plugin::NAMESPACE . '\\extensions\\access-control-visitor-ip';
    public const CACHE_CONTROL = Plugin::NAMESPACE . '\\extensions\\cache-control';
    public const EVENTS_MANAGER = Plugin::NAMESPACE . '\\extensions\\events-manager';
    public const FIELD_DEPRECATION = Plugin::NAMESPACE . '\\extensions\\field-deprecation';
    public const GOOGLE_TRANSLATE = Plugin::NAMESPACE . '\\extensions\\google-translate';

    /**
     * @return string[]
     */
    public function getModulesToResolve(): array
    {
        return [
            self::GATO_GRAPHQL_PRO,
            self::ACCESS_CONTROL,
            self::ACCESS_CONTROL_VISITOR_IP,
            self::CACHE_CONTROL,
            self::EVENTS_MANAGER,
            self::FIELD_DEPRECATION,
            self::GOOGLE_TRANSLATE,
        ];
    }

    public function getName(string $module): string
    {
        return match ($module) {
            self::GATO_GRAPHQL_PRO => \__('Gato GraphQL PRO', 'gato-graphql'),
            self::ACCESS_CONTROL => \__('Access Control', 'gato-graphql'),
            self::ACCESS_CONTROL_VISITOR_IP => \__('Access Control: Visitor IP', 'gato-graphql'),
            self::CACHE_CONTROL => \__('Cache Control', 'gato-graphql'),
            self::EVENTS_MANAGER => \__('Events Manager', 'gato-graphql'),
            self::FIELD_DEPRECATION => \__('Field Deprecation', 'gato-graphql'),
            self::GOOGLE_TRANSLATE => \__('Google Translate', 'gato-graphql'),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        return match ($module) {
            self::GATO_GRAPHQL_PRO => \__('Superpower your app with PRO features: Access Control, Cache Control, Multiple Query Execution, and more.', 'gato-graphql'),
            self::ACCESS_CONTROL => \__('Grant user access to schema elements via Access Control Lists.', 'gato-graphql'),
            self::ACCESS_CONTROL_VISITOR_IP => \__('Grant access to schema elements based on the visitor\'s IP address (Access Control extension is rquired).', 'gato-graphql'),
            self::CACHE_CONTROL => \__('Provide HTTP Caching for endpoints accessed via GET, with the max-age value automatically calculated from the query.', 'gato-graphql'),
            self::EVENTS_MANAGER => \__('Integration with plugin "Events Manager", adding fields to the schema to fetch event data.', 'gato-graphql'),
            self::FIELD_DEPRECATION => \__('Deprecate fields, and explain how to replace them, through a user interface.', 'gato-graphql'),
            self::GOOGLE_TRANSLATE => \__('Translate content to multiple languages using the Google Translate API.', 'gato-graphql'),
            default => parent::getDescription($module),
        };
    }

    public function getGatoGraphQLExtensionSlug(string $module): string
    {
        return match ($module) {
            self::GATO_GRAPHQL_PRO => $this->getSlug($module),
            default => parent::getGatoGraphQLExtensionSlug($module),
        };
    }

    public function getWebsiteURL(string $module): string
    {
        if ($module === self::GATO_GRAPHQL_PRO) {
            /** @var ModuleConfiguration */
            $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
            return sprintf(
                '%s/pro',
                $moduleConfiguration->getGatoGraphQLWebsiteURL()
            );
        }
        
        return parent::getWebsiteURL($module);
    }

    public function getLogoURL(string $module): string
    {
        $logoURL = parent::getLogoURL($module);
        if ($module === self::GATO_GRAPHQL_PRO) {
            return $logoURL;
        }

        return str_replace(
            'GatoGraphQL-logo.png',
            'GatoGraphQL-logo2.png',
            $logoURL,
        );
    }
}
