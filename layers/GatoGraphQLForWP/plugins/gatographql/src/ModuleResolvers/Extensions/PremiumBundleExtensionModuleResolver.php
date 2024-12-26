<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions;

use GatoGraphQL\GatoGraphQL\Plugin;
use GatoGraphQL\GatoGraphQL\PluginApp;
use GatoGraphQL\GatoGraphQL\PluginStaticModuleConfiguration;

class PremiumBundleExtensionModuleResolver extends AbstractBundleExtensionModuleResolver
{
    use PremiumExtensionModuleResolverTrait;

    public const POLYLANG = Plugin::NAMESPACE . '\\bundle-extensions\\polylang';

    /**
     * @return string[]
     */
    public function getModulesToResolve(): array
    {
        return PluginStaticModuleConfiguration::displayGatoGraphQLPROFeatureBundlesOnExtensionsPage()
            ? [
                self::POLYLANG,
            ] : [];
    }

    public function getName(string $module): string
    {
        $extensionPlaceholder = \__('%s', 'gatographql');
        return match ($module) {
            self::POLYLANG => sprintf($extensionPlaceholder, \__('Polylang', 'gatographql')),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        return match ($module) {
            self::POLYLANG => \__('Integration with the Polylang plugin, providing fields to the GraphQL schema to fetch multilingual data', 'gatographql'),
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
            self::POLYLANG
                => $pluginURL . 'assets/img/extension-logos/polylang.png',
            default => parent::getLogoURL($module),
        };
    }

    /**
     * @return string[]
     */
    public function getBundledExtensionModules(string $module): array
    {
        return match ($module) {
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
