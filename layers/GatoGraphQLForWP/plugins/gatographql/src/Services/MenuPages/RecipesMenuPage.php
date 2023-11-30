<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\Constants\HTMLCodes;
use GatoGraphQL\GatoGraphQL\ContentProcessors\ContentParserOptions;
use GatoGraphQL\GatoGraphQL\ContentProcessors\NoDocsFolderPluginMarkdownContentRetrieverTrait;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions\BundleExtensionModuleResolver;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions\ExtensionModuleResolverInterface;
use GatoGraphQL\GatoGraphQL\Registries\ModuleRegistryInterface;
use GatoGraphQL\GatoGraphQL\Services\Aggregators\BundleExtensionAggregator;
use GatoGraphQL\GatoGraphQL\Services\DataProviders\RecipeDataProvider;

class RecipesMenuPage extends AbstractVerticalTabDocsMenuPage
{
    use NoDocsFolderPluginMarkdownContentRetrieverTrait;

    private ?ModuleRegistryInterface $moduleRegistry = null;
    private ?RecipeDataProvider $recipeDataProvider = null;
    private ?BundleExtensionAggregator $bundleExtensionAggregator = null;

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
    final public function setRecipeDataProvider(RecipeDataProvider $recipeDataProvider): void
    {
        $this->recipeDataProvider = $recipeDataProvider;
    }
    final protected function getRecipeDataProvider(): RecipeDataProvider
    {
        if ($this->recipeDataProvider === null) {
            /** @var RecipeDataProvider */
            $recipeDataProvider = $this->instanceManager->getInstance(RecipeDataProvider::class);
            $this->recipeDataProvider = $recipeDataProvider;
        }
        return $this->recipeDataProvider;
    }
    final public function setBundleExtensionAggregator(BundleExtensionAggregator $bundleExtensionAggregator): void
    {
        $this->bundleExtensionAggregator = $bundleExtensionAggregator;
    }
    final protected function getBundleExtensionAggregator(): BundleExtensionAggregator
    {
        if ($this->bundleExtensionAggregator === null) {
            /** @var BundleExtensionAggregator */
            $bundleExtensionAggregator = $this->instanceManager->getInstance(BundleExtensionAggregator::class);
            $this->bundleExtensionAggregator = $bundleExtensionAggregator;
        }
        return $this->bundleExtensionAggregator;
    }

    public function getMenuPageSlug(): string
    {
        return 'recipes';
    }

    protected function getPageTitle(): string
    {
        return \__('Gato GraphQL - Recipes', 'gatographql');
    }

    protected function getContentID(): string
    {
        return 'gatographql-recipes';
    }

    /**
     * @param array{0:string,1:string} $entry
     */
    protected function getEntryRelativePathDir(array $entry): string
    {
        return 'docs/recipes';
    }

    // protected function enumerateEntries(): bool
    // {
    //     return true;
    // }

    protected function hasCollapsibleContent(): bool
    {
        return true;
    }

    protected function getDocsFolder(): string
    {
        return '';
    }

    /**
     * @return array<string,mixed>
     */
    protected function getMarkdownContentOptions(): array
    {
        $siteURL = str_replace(['https://', 'http://'], '', \get_site_url());
        return [
            ...parent::getMarkdownContentOptions(),
            ContentParserOptions::REPLACEMENTS => [
                'mysite.com' => $siteURL,
            ],
        ];
    }

    /**
     * @param array{0:string,1:string,2?:string[],3?:string[]} $entry
     */
    protected function getEntryContent(
        string $entryContent,
        array $entry,
    ): string {
        $entryExtensionModules = $entry[2] ?? [];
        if ($entryExtensionModules === []) {
            return $entryContent;
        }

        $messageExtensionPlaceholder = '<ul><li>%s</li></ul>';

        $extensionHTMLItems = $this->getExtensionHTMLItems($entryExtensionModules);

        $entryBundleExtensionModules = $entry[3] ?? [];
        $entryBundleExtensionModules[] = BundleExtensionModuleResolver::ALL_EXTENSIONS;
        $bundleExtensionHTMLItems = $this->getExtensionHTMLItems($entryBundleExtensionModules);
        $messageBundleExtensionPlaceholder = sprintf(
            '<hr/><em>%s</em>',
            count($entryExtensionModules) === 1
                ? \__('(It is included in %s)', 'gatographql')
                : \__('(They are all included in %s)', 'gatographql')
        );

        $messageHTML = sprintf(
            \__('<strong>🔗 %s</strong>: %s', 'gatographql'),
            \__('Extensions referenced in this recipe', 'gatographql'),
            $this->getCollapsible(
                sprintf(
                    '%s%s',
                    sprintf(
                        $messageExtensionPlaceholder,
                        implode(
                            '</li><li>',
                            $extensionHTMLItems
                        )
                    ),
                    sprintf(
                        $messageBundleExtensionPlaceholder,
                        implode(
                            \__(', ', 'gatographql'),
                            $bundleExtensionHTMLItems
                        )
                    )
                )
            )
        );

        return sprintf(
            '<div class="%s">%s</div>',
            'extension-highlight',
            $messageHTML,
        ) . $entryContent;
    }

    /**
     * @param string[] $entryExtensionModules
     * @return string[]
     */
    protected function getExtensionHTMLItems(
        array $entryExtensionModules,
    ): array {
        $extensionHTMLItems = [];
        foreach ($entryExtensionModules as $entryExtensionModule) {
            /** @var ExtensionModuleResolverInterface */
            $extensionModuleResolver = $this->getModuleRegistry()->getModuleResolver($entryExtensionModule);
            $extensionHTMLItems[] = sprintf(
                \__('<strong><a href="%s" target="%s">%s%s</a></strong>', 'gatographql'),
                $extensionModuleResolver->getWebsiteURL($entryExtensionModule),
                '_blank',
                $extensionModuleResolver->getName($entryExtensionModule),
                HTMLCodes::OPEN_IN_NEW_WINDOW
            );
        }
        return $extensionHTMLItems;
    }

    /**
     * @return array<array{0:string,1:string,2?:string[],3?:string[]}> Value: [0] => recipe file slug, [1] => title, [2] => array of extension modules, [3] => array of bundle modules
     */
    protected function getEntries(): array
    {
        $bundleExtensionAggregator = $this->getBundleExtensionAggregator();
        $entries = [];
        foreach ($this->getRecipeDataProvider()->getRecipeSlugDataItems() as $recipeSlug => $recipeDataItem) {
            /** @var string */
            $recipeTitle = $recipeDataItem[0];
            /** @var string[] */
            $recipeExtensionModules = $recipeDataItem[1];
            $recipeBundleModules = $bundleExtensionAggregator->getBundleModulesComprisingAllExtensionModules($recipeExtensionModules);
            $entries[] = [
                $recipeSlug,
                [
                    $recipeTitle,
                    $recipeExtensionModules,
                    $recipeBundleModules,
                ]
            ];
        }
        return $entries;
    }

    /**
     * Enqueue the required assets
     */
    protected function enqueueAssets(): void
    {
        parent::enqueueAssets();

        $this->enqueueResponsiveVideoContainerAssets();
    }
}
