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
use GatoGraphQL\GatoGraphQL\Services\MenuPages\ExtensionsMenuPage;

class ExtensionDocsMenuPage extends AbstractVerticalTabDocsMenuPage
{
    use NoDocsFolderPluginMarkdownContentRetrieverTrait;

    private ?ModuleRegistryInterface $moduleRegistry = null;
    private ?ExtensionsMenuPage $extensionsMenuPage = null;

    final public function setModuleRegistry(ModuleRegistryInterface $moduleRegistry): void
    {
        $this->moduleRegistry = $moduleRegistry;
    }
    final protected function getModuleRegistry(): ModuleRegistryInterface
    {
        /** @var ModuleRegistryInterface */
        return $this->moduleRegistry ??= $this->instanceManager->getInstance(ModuleRegistryInterface::class);
    }
    final public function setExtensionsMenuPage(ExtensionsMenuPage $extensionsMenuPage): void
    {
        $this->extensionsMenuPage = $extensionsMenuPage;
    }
    final protected function getExtensionsMenuPage(): ExtensionsMenuPage
    {
        /** @var ExtensionsMenuPage */
        return $this->extensionsMenuPage ??= $this->instanceManager->getInstance(ExtensionsMenuPage::class);
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
        $extensionsMenuPage = $this->getExtensionsMenuPage();
        return sprintf(
            '<p>%s</p>',
            sprintf(
                __('%s <a href="%s" class="button">Switch to the <strong>Extensions</strong> view</a></span>', 'gato-graphql'),
                $extensionsMenuPage->getHeaderMessage(),
                \admin_url(sprintf(
                    'admin.php?page=%s',
                    $extensionsMenuPage->getScreenID()
                )),
            )
        );
    }

    /**
     * @return array<array{0:string,1:string,2:string}>
     */
    protected function getEntries(): array
    {
        $entries = [];
        foreach ($this->getExtensionModuleItems() as $extensionModuleItem) {
            /** @var string */
            $extensionSlug = $extensionModuleItem['slug'];
            /** @var string */
            $extensionName = $extensionModuleItem['name'];
            /** @var string */
            $extensionModule = $extensionModuleItem['module'];
            $entries[] = [
                sprintf(
                    '%1$s/docs/modules/%1$s',
                    $extensionSlug
                ),
                $extensionName,
                $extensionModule,
            ];
        }
        return $entries;
    }

    /**
     * @return array<array{slug:string,name:string,module:string}>
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
            $items[] = [
                'slug' => $moduleResolver->getSlug($module),
                'name' => $moduleResolver->getName($module),
                'module' => $module,
                'module-resolver' => $moduleResolver,
            ];
        }
        return $items;
    }

    /**
     * @param array<array{slug:string,name:string,module:string}> $entry
     */
    protected function getEntryTitle(
        string $entryTitle,
        array $entry,
    ): string {
        /** @var string */
        $entryModule = $entry[2];
        /** @var ExtensionModuleResolverInterface */
        $entryModuleResolver = $this->getModuleRegistry()->getModuleResolver($entryModule);
        return sprintf(
            \__('%s <small>(<a href="%s" target="%s" title="%s">get extension%s</a>)</small>', 'gato-graphql'),
            $entryTitle,
            $entryModuleResolver->getWebsiteURL($entryModule),
            '_blank',
            \__('Open in shop', 'gato-graphql'),
            HTMLCodes::OPEN_IN_NEW_WINDOW
        );
    }
}
