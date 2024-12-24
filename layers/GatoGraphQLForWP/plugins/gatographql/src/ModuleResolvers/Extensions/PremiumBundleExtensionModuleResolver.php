<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions;

use GatoGraphQL\GatoGraphQL\Plugin;
use GatoGraphQL\GatoGraphQL\PluginApp;
use GatoGraphQL\GatoGraphQL\PluginStaticModuleConfiguration;

class PremiumBundleExtensionModuleResolver extends AbstractBundleExtensionModuleResolver
{
    public const POLYLANG_INTEGRATION = Plugin::NAMESPACE . '\\bundle-extensions\\polylang-integration';

    /**
     * @return string[]
     */
    public function getModulesToResolve(): array
    {
        return PluginStaticModuleConfiguration::displayGatoGraphQLPROFeatureBundlesOnExtensionsPage()
            ? [
                self::POLYLANG_INTEGRATION,
            ] : [];
    }

    public function getName(string $module): string
    {
        $extensionPlaceholder = \__('%s', 'gatographql');
        return match ($module) {
            self::POLYLANG_INTEGRATION => sprintf($extensionPlaceholder, \__('Polylang Integration', 'gatographql')),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        return match ($module) {
            self::POLYLANG_INTEGRATION => \__('Integration with the Polylang plugin, providing fields to the GraphQL schema to fetch multilingual data', 'gatographql'),
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
        return match ($module) {
            self::POLYLANG_INTEGRATION
                => $pluginURL . 'assets/img/extension-logos/polylang-integration.png',
            default => parent::getLogoURL($module),
        };
    }

    /**
     * @return string[]
     */
    public function getBundledExtensionModules(string $module): array
    {
        return match ($module) {
            self::POLYLANG_INTEGRATION => [
                ExtensionModuleResolver::POLYLANG,
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

    public function isPremium(string $module): bool
    {
        return true;
    }
}
