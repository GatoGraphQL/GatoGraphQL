<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions;

use GatoGraphQL\GatoGraphQL\App;
use GatoGraphQL\GatoGraphQL\ContentProcessors\BundleExtensionPluginMarkdownContentRetrieverTrait;
use GatoGraphQL\GatoGraphQL\Module;
use GatoGraphQL\GatoGraphQL\ModuleConfiguration;
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

    public function getWebsiteURL(string $module): string
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return sprintf(
            '%s/bundles/%s',
            $moduleConfiguration->getGatoGraphQLWebsiteURL(),
            $this->getSlug($module)
        );
    }

    public function getLogoURL(string $module): string
    {
        $mainPlugin = PluginApp::getMainPlugin();
        $pluginURL = $mainPlugin->getPluginURL();
        return $pluginURL . 'assets/img/logos/GatoGraphQL-logo-back-long.png';
    }

    public function getGatoGraphQLExtensionSlug(string $module): string
    {
        return parent::getGatoGraphQLExtensionSlug($module) . '-bundle';
    }

    /**
     * @return string[]
     */
    final public function getGatoGraphQLBundledExtensionSlugs(string $module): array
    {
        return array_map(
            fn (string $extensionSlug) => 'gato-graphql-' . $extensionSlug,
            $this->getBundledExtensionSlugs($module)
        );
    }
}
