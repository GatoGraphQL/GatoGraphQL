<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions;

use GatoGraphQL\GatoGraphQL\App;
use GatoGraphQL\GatoGraphQL\Module;
use GatoGraphQL\GatoGraphQL\ModuleConfiguration;
use GatoGraphQL\GatoGraphQL\Plugin;

class ExtensionModuleResolver extends AbstractExtensionModuleResolver
{
    private const GATO_GRAPHQL_PRO = Plugin::NAMESPACE . '\\extensions\\gato-graphql-pro';
    private const ACCESS_CONTROL_VISITOR_IP = Plugin::NAMESPACE . '\\extensions\\access-control-visitor-ip';
    private const GOOGLE_TRANSLATE = Plugin::NAMESPACE . '\\extensions\\google-translate';
    private const EVENTS_MANAGER = Plugin::NAMESPACE . '\\extensions\\events-manager';

    /**
     * @return string[]
     */
    public function getModulesToResolve(): array
    {
        return [
            self::GATO_GRAPHQL_PRO,
            self::ACCESS_CONTROL_VISITOR_IP,
            self::GOOGLE_TRANSLATE,
            self::EVENTS_MANAGER,
        ];
    }

    public function getName(string $module): string
    {
        return match ($module) {
            self::GATO_GRAPHQL_PRO => \__('Gato GraphQL PRO', 'gato-graphql'),
            self::ACCESS_CONTROL_VISITOR_IP => \__('Access Control: Visitor IP', 'gato-graphql'),
            self::GOOGLE_TRANSLATE => \__('Google Translate', 'gato-graphql'),
            self::EVENTS_MANAGER => \__('Events Manager', 'gato-graphql'),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        return match ($module) {
            self::GATO_GRAPHQL_PRO => \__('Superpower your application with PRO features: Access Control, Cache Control, Multiple Query Execution, and many more.', 'gato-graphql'),
            self::ACCESS_CONTROL_VISITOR_IP => \__('Grant access to schema elements based on the visitor\'s IP address (Gato GraphQL PRO is rquired).', 'gato-graphql'),
            self::GOOGLE_TRANSLATE => \__('Translate content to multiple languages using the Google Translate API.', 'gato-graphql'),
            self::EVENTS_MANAGER => \__('Integration with plugin "Events Manager", adding fields to the GraphQL schema to fetch event data.', 'gato-graphql'),
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
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return match ($module) {
            self::GATO_GRAPHQL_PRO => sprintf(
                '%s/pro',
                $moduleConfiguration->getGatoGraphQLWebsiteURL()
            ),
            default => parent::getWebsiteURL($module),
        };
    }
}
