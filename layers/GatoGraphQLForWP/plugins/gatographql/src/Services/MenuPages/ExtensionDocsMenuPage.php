<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\Constants\HTMLCodes;
use GatoGraphQL\GatoGraphQL\ContentProcessors\NoDocsFolderPluginMarkdownContentRetrieverTrait;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions\BundleExtensionModuleResolverInterface;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions\ExtensionModuleResolverInterface;
use GatoGraphQL\GatoGraphQL\PluginStaticModuleConfiguration;
use GatoGraphQL\GatoGraphQL\Registries\ModuleRegistryInterface;
use GatoGraphQL\GatoGraphQL\Services\MenuPages\ExtensionsMenuPage;

class ExtensionDocsMenuPage extends AbstractVerticalTabDocsMenuPage
{
    use OpenInModalTriggerMenuPageTrait;
    use NoDocsFolderPluginMarkdownContentRetrieverTrait;

    private ?ModuleRegistryInterface $moduleRegistry = null;
    private ?ExtensionsMenuPage $extensionsMenuPage = null;

    final public function setModuleRegistry(ModuleRegistryInterface $moduleRegistry): void
    {
        $this->moduleRegistry = $moduleRegistry;
    }
    final protected function getModuleRegistry(): ModuleRegistryInterface
    {
        if ($this->moduleRegistry === null) {
            /** @var ModuleRegistryInterface */
            $moduleRegistry = $this->instanceManager->getInstance(ModuleRegistryInterface::class);
            $this->moduleRegistry = $moduleRegistry;
        }
        return $this->moduleRegistry;
    }
    final public function setExtensionsMenuPage(ExtensionsMenuPage $extensionsMenuPage): void
    {
        $this->extensionsMenuPage = $extensionsMenuPage;
    }
    final protected function getExtensionsMenuPage(): ExtensionsMenuPage
    {
        if ($this->extensionsMenuPage === null) {
            /** @var ExtensionsMenuPage */
            $extensionsMenuPage = $this->instanceManager->getInstance(ExtensionsMenuPage::class);
            $this->extensionsMenuPage = $extensionsMenuPage;
        }
        return $this->extensionsMenuPage;
    }

    public function getMenuPageSlug(): string
    {
        return 'extensiondocs';
    }

    protected function getPageTitle(): string
    {
        return \__('Gato GraphQL - Extension Docs', 'gatographql');
    }

    protected function getContentID(): string
    {
        return 'gatographql-extension-docs';
    }

    /**
     * @param array{0:string,1:string,2:string} $entry
     * @phpstan-ignore-next-line
     */
    protected function getEntryRelativePathDir(array $entry): string
    {
        /** @var string */
        $entryModule = $entry[2];
        /** @var ExtensionModuleResolverInterface */
        $entryModuleResolver = $this->getModuleRegistry()->getModuleResolver($entryModule);
        if ($entryModuleResolver instanceof BundleExtensionModuleResolverInterface) {
            return 'bundle-extensions';
        }
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
                __('%s <a href="%s" class="button">Switch to the <strong>Extensions</strong> view</a></span>', 'gatographql'),
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
            ];
        }
        return $items;
    }

    /**
     * @param array<array{0:string,1:string,2:string}> $entry
     * @phpstan-ignore-next-line
     */
    protected function getEntryTitle(
        string $entryTitle,
        array $entry,
    ): string {
        /** @var string */
        $entryModule = $entry[2];
        /** @var ExtensionModuleResolverInterface */
        $entryModuleResolver = $this->getModuleRegistry()->getModuleResolver($entryModule);
        $offerGatoGraphQLPROBundle = PluginStaticModuleConfiguration::offerGatoGraphQLPROBundle();
        $offerGatoGraphQLPROFeatureBundles = PluginStaticModuleConfiguration::offerGatoGraphQLPROFeatureBundles();
        return sprintf(
            \__('%s <small>(<a href="%s" target="%s" title="%s">%s%s</a>)</small>', 'gatographql'),
            $entryTitle,
            $entryModuleResolver->getWebsiteURL($entryModule),
            '_blank',
            \__('Open in shop', 'gatographql'),
            $entryModuleResolver instanceof BundleExtensionModuleResolverInterface
                ? ($offerGatoGraphQLPROBundle && !$offerGatoGraphQLPROFeatureBundles ? \__('go PRO', 'gatographql') : \__('get bundle', 'gatographql'))
                : ($offerGatoGraphQLPROBundle && !$offerGatoGraphQLPROFeatureBundles ? \__('visit on website', 'gatographql') : \__('get extension', 'gatographql')),
            HTMLCodes::OPEN_IN_NEW_WINDOW
        );
    }

    /**
     * Enqueue the required assets
     */
    protected function enqueueAssets(): void
    {
        parent::enqueueAssets();

        $this->enqueueModalTriggerAssets();

        $this->enqueueResponsiveVideoContainerAssets();
    }
}
