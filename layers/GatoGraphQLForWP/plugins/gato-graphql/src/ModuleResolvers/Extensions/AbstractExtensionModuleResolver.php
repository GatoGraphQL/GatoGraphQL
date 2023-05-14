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
        /** @var MarkdownContentParserInterface */
        return $this->markdownContentParser ??= $this->instanceManager->getInstance(MarkdownContentParserInterface::class);
    }

    /**
     * The type of the module doesn't really matter,
     * as these modules are all hidden anyway
     */
    public function getModuleType(string $module): string
    {
        return ModuleTypeResolver::EXTENSION;
    }

    public function isHidden(string $module): bool
    {
        return true;
    }

    public function isPredefinedEnabledOrDisabled(string $module): ?bool
    {
        return true;
    }

    public function getGatoGraphQLExtensionSlug(string $module): string
    {
        return 'gato-graphql-' . $this->getSlug($module);
    }

    public function getWebsiteURL(string $module): string
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return sprintf(
            '%s/extensions/%s',
            $moduleConfiguration->getGatoGraphQLWebsiteURL(),
            $this->getSlug($module)
        );
    }

    public function getLogoURL(string $module): string
    {
        $mainPlugin = PluginApp::getMainPlugin();
        $pluginURL = $mainPlugin->getPluginURL();
        return $pluginURL . 'assets-pro/img/GatoGraphQL-logo.svg';
    }

    protected function getDocumentationMarkdownContentRelativePathDir(
        string $module,
    ): string {
        return $this->getSlug($module) . '/docs/modules';
    }
}
