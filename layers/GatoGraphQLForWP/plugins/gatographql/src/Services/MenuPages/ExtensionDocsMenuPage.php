<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\Constants\HTMLCodes;
use GatoGraphQL\GatoGraphQL\ContentProcessors\NoDocsFolderPluginMarkdownContentRetrieverTrait;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions\BundleExtensionModuleResolverInterface;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions\ExtensionModuleResolverInterface;
use GatoGraphQL\GatoGraphQL\PluginStaticModuleConfiguration;
use GatoGraphQL\GatoGraphQL\Services\MenuPages\ExtensionsMenuPage;

class ExtensionDocsMenuPage extends AbstractVerticalTabDocsMenuPage
{
    use OpenInModalTriggerMenuPageTrait;
    use NoDocsFolderPluginMarkdownContentRetrieverTrait;

    private ?ExtensionsMenuPage $extensionsMenuPage = null;

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

    public function getMenuPageTitle(): string
    {
        return __('Extension Reference Docs', 'gatographql');
    }

    public function isServiceEnabled(): bool
    {
        return $this->getExtensionsMenuPage()->isServiceEnabled();
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
                __('%s <a href="%s" class="button">Back to the <strong>Extensions</strong> page</a></span>', 'gatographql'),
                $extensionsMenuPage->getHeaderMessage(),
                \admin_url(sprintf(
                    'admin.php?page=%s',
                    $extensionsMenuPage->getScreenID()
                )),
            )
        );
    }

    /**
     * @return array<array{0:string,1:string,2:string}> Value: [0] => slug, [1] => name, [2] => module
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
        $displayGatoGraphQLPROExtensionsOnExtensionsPage = PluginStaticModuleConfiguration::displayGatoGraphQLPROExtensionsOnExtensionsPage();
        foreach ($modules as $module) {
            $moduleResolver = $moduleRegistry->getModuleResolver($module);
            if (!($moduleResolver instanceof ExtensionModuleResolverInterface)) {
                continue;
            }
            $isBundleExtension = $moduleResolver instanceof BundleExtensionModuleResolverInterface;
            if (!$isBundleExtension && !$displayGatoGraphQLPROExtensionsOnExtensionsPage) {
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
        $displayGatoGraphQLPROBundleOnExtensionsPage = PluginStaticModuleConfiguration::displayGatoGraphQLPROBundleOnExtensionsPage();
        $displayGatoGraphQLPROFeatureBundlesOnExtensionsPage = PluginStaticModuleConfiguration::displayGatoGraphQLPROFeatureBundlesOnExtensionsPage();
        return sprintf(
            \__('%s <small>(<a href="%s" target="%s" title="%s">%s%s</a>)</small>', 'gatographql'),
            $entryTitle,
            $entryModuleResolver->getWebsiteURL($entryModule),
            '_blank',
            \__('Open in shop', 'gatographql'),
            $entryModuleResolver instanceof BundleExtensionModuleResolverInterface
                ? ($displayGatoGraphQLPROBundleOnExtensionsPage && !$displayGatoGraphQLPROFeatureBundlesOnExtensionsPage ? \__('go PRO', 'gatographql') : \__('website', 'gatographql'))
                : \__('website', 'gatographql'),
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

    /**
     * Print the bundled extensions using a tabPanel
     */
    protected function useTabpanelForContent(): bool
    {
        if (!PluginStaticModuleConfiguration::displayGatoGraphQLPROExtensionsOnExtensionsPage()) {
            return true;
        }
        return parent::useTabpanelForContent();
    }

    /**
     * @param array{0:string,1:string,2:mixed} $entry
     */
    protected function getAdditionalEntryContentToPrint(array $entry): string
    {
        $markdownContentOptions = $this->getMarkdownContentOptions();
        $entryModule = $entry[2];

        // Add the content for the bundled extensions
        $entryModuleResolver = $this->getModuleRegistry()->getModuleResolver($entryModule);
        $isBundleExtension = $entryModuleResolver instanceof BundleExtensionModuleResolverInterface;
        if ($isBundleExtension) {
            $contentEntries = [];

            /** @var BundleExtensionModuleResolverInterface */
            $bundleExtensionModuleResolver = $entryModuleResolver;
            $bundleExtensionModules = $bundleExtensionModuleResolver->getBundledExtensionModules($entryModule);
            foreach ($bundleExtensionModules as $bundleExtensionModule) {
                /** @var ExtensionModuleResolverInterface */
                $extensionModuleResolver = $this->getModuleRegistry()->getModuleResolver($bundleExtensionModule);
                $entryModuleName = sprintf(
                    '%1$s/docs/modules/%1$s',
                    $extensionModuleResolver->getSlug($bundleExtensionModule)
                );
                $entryModuleContent = $this->getMarkdownContent(
                    $entryModuleName,
                    $this->getEntryRelativePathDir([
                        $entryModuleName,
                        $extensionModuleResolver->getName($bundleExtensionModule),
                        $bundleExtensionModule,
                    ]),
                    $markdownContentOptions
                ) ?? '';
                $contentEntries[] = $entryModuleContent;
            }

            if ($contentEntries !== []) {
                return sprintf(
                    '
                    <br/>
                    <p><u><strong>%s</strong></u></p>
                    ',
                    PluginStaticModuleConfiguration::displayGatoGraphQLPROFeatureBundlesOnExtensionsPage()
                        && !PluginStaticModuleConfiguration::displayGatoGraphQLPROBundleOnExtensionsPage()
                        // && !PluginStaticModuleConfiguration::displayGatoGraphQLPROAllExtensionsBundleOnExtensionsPage()
                        ? \__('Modules included in this extension:', 'gatographql')
                        : \__('Modules included in this bundle:', 'gatographql')
                ) . implode('<br/><hr/>', $contentEntries);
            }
        }

        return '';
    }
}
