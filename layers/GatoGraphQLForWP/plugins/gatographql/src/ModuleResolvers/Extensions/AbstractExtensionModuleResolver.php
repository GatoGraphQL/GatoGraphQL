<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions;

use GatoGraphQL\GatoGraphQL\App;
use GatoGraphQL\GatoGraphQL\ContentProcessors\MarkdownContentParserInterface;
use GatoGraphQL\GatoGraphQL\Module;
use GatoGraphQL\GatoGraphQL\ModuleConfiguration;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\AbstractModuleResolver;
use GatoGraphQL\GatoGraphQL\PluginApp;
use GatoGraphQL\GatoGraphQL\Services\ModuleTypeResolvers\ModuleTypeResolver;
use GatoGraphQL\GatoGraphQL\SettingsCategoryResolvers\SettingsCategoryResolver;

/**
 * Container modules to display documentation for extensions
 * in the Extensions page.
 */
abstract class AbstractExtensionModuleResolver extends AbstractModuleResolver implements ExtensionModuleResolverInterface
{
    use ExtensionModuleResolverTrait;

    private ?MarkdownContentParserInterface $markdownContentParser = null;

    final public function setMarkdownContentParser(MarkdownContentParserInterface $markdownContentParser): void
    {
        $this->markdownContentParser = $markdownContentParser;
    }
    final protected function getMarkdownContentParser(): MarkdownContentParserInterface
    {
        if ($this->markdownContentParser === null) {
            /** @var MarkdownContentParserInterface */
            $markdownContentParser = $this->instanceManager->getInstance(MarkdownContentParserInterface::class);
            $this->markdownContentParser = $markdownContentParser;
        }
        return $this->markdownContentParser;
    }

    /**
     * The type of the module doesn't really matter,
     * as these modules are all hidden anyway
     */
    public function getModuleType(string $module): string
    {
        return ModuleTypeResolver::EXTENSION;
    }

    public function getSettingsCategory(string $module): string
    {
        return SettingsCategoryResolver::PLUGIN_MANAGEMENT;
    }

    public function isHidden(string $module): bool
    {
        return true;
    }

    public function isPredefinedEnabledOrDisabled(string $module): ?bool
    {
        return true;
    }

    public function getGatoGraphQLExtensionPluginFile(string $module): string
    {
        $extensionSlug = $this->getGatoGraphQLExtensionSlug($module);
        return sprintf(
            '%1$s/%1$s.php',
            $extensionSlug
        );
    }

    public function getGatoGraphQLExtensionSlug(string $module): string
    {
        return $this->addGatoGraphQLPrefixToExtensionSlug($this->getSlug($module));
    }

    protected function addGatoGraphQLPrefixToExtensionSlug(string $extensionSlug): string
    {
        return 'gatographql-' . $extensionSlug;
    }

    public function getWebsiteURL(string $module): string
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return sprintf(
            '%s/extensions-reference/%s/',
            $moduleConfiguration->getGatoGraphQLWebsiteURL(),
            $this->getSlug($module)
        );
    }

    public function getLogoURL(string $module): string
    {
        $mainPlugin = PluginApp::getMainPlugin();
        $pluginURL = $mainPlugin->getPluginURL();
        return $pluginURL . 'assets/img/logos/GatoGraphQL-logo-paws.png';
    }

    protected function getDocumentationMarkdownContentRelativePathDir(
        string $module,
    ): string {
        return $this->getSlug($module) . '/docs/modules';
    }
}
