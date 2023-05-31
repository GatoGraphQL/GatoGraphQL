<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\App;
use GatoGraphQL\GatoGraphQL\Constants\HTMLCodes;
use GatoGraphQL\GatoGraphQL\ContentProcessors\NoDocsFolderPluginMarkdownContentRetrieverTrait;
use GatoGraphQL\GatoGraphQL\Module;
use GatoGraphQL\GatoGraphQL\ModuleConfiguration;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions\ExtensionModuleResolverInterface;
use GatoGraphQL\GatoGraphQL\Registries\ModuleRegistryInterface;

class ExtensionDocsMenuPage extends AbstractVerticalTabDocsMenuPage
{
    use NoDocsFolderPluginMarkdownContentRetrieverTrait;

    private ?ModuleRegistryInterface $moduleRegistry = null;

    final public function setModuleRegistry(ModuleRegistryInterface $moduleRegistry): void
    {
        $this->moduleRegistry = $moduleRegistry;
    }
    final protected function getModuleRegistry(): ModuleRegistryInterface
    {
        /** @var ModuleRegistryInterface */
        return $this->moduleRegistry ??= $this->instanceManager->getInstance(ModuleRegistryInterface::class);
    }

    public function getMenuPageSlug(): string
    {
        return 'extensiondocs';
    }

    protected function getPageTitle(): string
    {
        return \__('Gato GraphQL - Extension Docs', 'gato-graphql');
    }

    protected function getContentID(): string
    {
        return 'gato-graphql-extension-docs';
    }

    protected function getEntryRelativePathDir(): string
    {
        return 'extensions';
    }

    protected function getDocsFolder(): string
    {
        return '';
    }

    protected function getPageHeaderHTML(): string
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return sprintf(
            '<p>%s</p>',
            sprintf(
                __('Extensions add functionality and expand the GraphQL schema. You can browse and get extensions on the <a href="%1$s" target="%2$s">Gato GraphQL shop%3$s</a>.', 'gato-graphql'),
                $moduleConfiguration->getPROPluginShopURL(),
                '_blank',
                HTMLCodes::OPEN_IN_NEW_WINDOW,
            )
        );
    }

    /**
     * @return array<array{0:string,1:string}>
     */
    protected function getEntries(): array
    {
        $entries = [];
        foreach ($this->getExtensionModuleItems() as $extensionSlug => $extensionName) {
            $entries[] = [
                sprintf(
                    '%1$s/docs/modules/%1$s',
                    $extensionSlug
                ),
                $extensionName,
            ];
        }
        return $entries;
    }

    /**
     * @return array<string,string> Key: extension slug, Value: extension name
     */
    protected function getExtensionModuleItems(): array
    {
        $moduleRegistry = $this->getModuleRegistry();
        $modules = $moduleRegistry->getAllModules(true, false, false);
        $items = [];
        foreach ($modules as $module) {
            $moduleResolver = $moduleRegistry->getModuleResolver($module);
            if (!($moduleResolver instanceof ExtensionModuleResolverInterface)) {
                continue;
            }
            $items[$moduleResolver->getSlug($module)] = $moduleResolver->getName($module);
        }
        return $items;
    }
}
