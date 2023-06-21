<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions;

use GatoGraphQL\GatoGraphQL\ContentProcessors\BundleExtensionPluginMarkdownContentRetrieverTrait;
use GatoGraphQL\GatoGraphQL\PluginApp;
use GatoGraphQL\GatoGraphQL\Services\ModuleTypeResolvers\ModuleTypeResolver;

/**
 * Container modules to display documentation for extensions
 * in the Extensions page.
 */
abstract class AbstractBundleExtensionModuleResolver extends AbstractExtensionModuleResolver implements BundleExtensionModuleResolverInterface
{
    use BundleExtensionPluginMarkdownContentRetrieverTrait;

    /**
     * The type of the module doesn't really matter,
     * as these modules are all hidden anyway
     */
    public function getModuleType(string $module): string
    {
        return ModuleTypeResolver::BUNDLE_EXTENSION;
    }

    public function getLogoURL(string $module): string
    {
        $mainPlugin = PluginApp::getMainPlugin();
        $pluginURL = $mainPlugin->getPluginURL();
        return $pluginURL . 'assets/img/logos/GatoGraphQL-logo-back.png';
    }

    public function getGatoGraphQLExtensionSlug(string $module): string
    {
        return parent::getGatoGraphQLExtensionSlug($module) . '-bundle';
    }
}
