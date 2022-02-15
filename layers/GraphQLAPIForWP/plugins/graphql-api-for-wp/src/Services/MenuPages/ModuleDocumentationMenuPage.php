<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\MenuPages;

use GraphQLAPI\GraphQLAPI\Constants\RequestParams;
use GraphQLAPI\GraphQLAPI\ContentProcessors\PluginMarkdownContentRetrieverTrait;
use GraphQLAPI\GraphQLAPI\Exception\ContentNotExistsException;
use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use PoP\Root\App;

/**
 * Module Documentation menu page
 */
class ModuleDocumentationMenuPage extends AbstractDocsMenuPage
{
    use PluginMarkdownContentRetrieverTrait;

    private ?ModuleRegistryInterface $moduleRegistry = null;
    private ?ModulesMenuPage $modulesMenuPage = null;

    final public function setModuleRegistry(ModuleRegistryInterface $moduleRegistry): void
    {
        $this->moduleRegistry = $moduleRegistry;
    }
    final protected function getModuleRegistry(): ModuleRegistryInterface
    {
        return $this->moduleRegistry ??= $this->instanceManager->getInstance(ModuleRegistryInterface::class);
    }
    final public function setModulesMenuPage(ModulesMenuPage $modulesMenuPage): void
    {
        $this->modulesMenuPage = $modulesMenuPage;
    }
    final protected function getModulesMenuPage(): ModulesMenuPage
    {
        return $this->modulesMenuPage ??= $this->instanceManager->getInstance(ModulesMenuPage::class);
    }

    public function getMenuPageSlug(): string
    {
        return $this->getModulesMenuPage()->getMenuPageSlug();
    }

    /**
     * Validate the param also
     */
    protected function isCurrentScreen(): bool
    {
        return $this->getMenuPageHelper()->isDocumentationScreen() && parent::isCurrentScreen();
    }

    protected function openInModalWindow(): bool
    {
        return true;
    }

    protected function useTabpanelForContent(): bool
    {
        return true;
    }

    protected function getContentToPrint(): string
    {
        // This is crazy: passing ?module=Foo\Bar\module,
        // and then doing $_GET['module'], returns "Foo\\Bar\\module"
        // So parse the URL to extract the "module" param
        $result = [];
        parse_str(App::server('REQUEST_URI'), $result);
        $module = urldecode($result[RequestParams::MODULE]);
        try {
            $moduleResolver = $this->getModuleRegistry()->getModuleResolver($module);
        } catch (ContentNotExistsException) {
            return sprintf(
                '<p>%s</p>',
                sprintf(
                    \__('Oops, module \'%s\' does not exist', 'graphql-api'),
                    $module
                )
            );
        }
        $hasDocumentation = $moduleResolver->hasDocumentation($module);
        $documentation = '';
        if ($hasDocumentation) {
            $documentation = $moduleResolver->getDocumentation($module);
        }
        if (!$hasDocumentation || $documentation === null) {
            return sprintf(
                '<p>%s</p>',
                sprintf(
                    \__('Oops, module \'%s\' has no documentation', 'graphql-api'),
                    $moduleResolver->getName($module)
                )
            );
        }
        return $documentation;
    }
}
