<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\App;
use GatoGraphQL\GatoGraphQL\Constants\HTMLCodes;
use GatoGraphQL\GatoGraphQL\ContentProcessors\ContentParserOptions;
use GatoGraphQL\GatoGraphQL\ContentProcessors\NoDocsFolderPluginMarkdownContentRetrieverTrait;
use GatoGraphQL\GatoGraphQL\Module;
use GatoGraphQL\GatoGraphQL\ModuleConfiguration;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions\ExtensionModuleResolverInterface;
use GatoGraphQL\GatoGraphQL\Registries\ModuleRegistryInterface;
use GatoGraphQL\GatoGraphQL\Services\Aggregators\BundleExtensionAggregator;
use GatoGraphQL\GatoGraphQL\Services\DataProviders\TutorialLessonDataProvider;

class TutorialMenuPage extends AbstractVerticalTabDocsMenuPage
{
    use NoDocsFolderPluginMarkdownContentRetrieverTrait;

    private ?ModuleRegistryInterface $moduleRegistry = null;
    private ?TutorialLessonDataProvider $tutorialLessonDataProvider = null;
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
    final public function setTutorialLessonDataProvider(TutorialLessonDataProvider $tutorialLessonDataProvider): void
    {
        $this->tutorialLessonDataProvider = $tutorialLessonDataProvider;
    }
    final protected function getTutorialLessonDataProvider(): TutorialLessonDataProvider
    {
        if ($this->tutorialLessonDataProvider === null) {
            /** @var TutorialLessonDataProvider */
            $tutorialLessonDataProvider = $this->instanceManager->getInstance(TutorialLessonDataProvider::class);
            $this->tutorialLessonDataProvider = $tutorialLessonDataProvider;
        }
        return $this->tutorialLessonDataProvider;
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
        return 'tutorial';
    }

    protected function getPageTitle(): string
    {
        return \__('Gato GraphQL - Tutorial', 'gatographql');
    }

    protected function getContentID(): string
    {
        return 'gatographql-tutorial';
    }

    /**
     * @param array{0:string,1:string} $entry
     */
    protected function getEntryRelativePathDir(array $entry): string
    {
        return 'docs/tutorial';
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

        $messageBundleExtensionContent = '';
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($moduleConfiguration->showBundlesContainingReferencedExtensionsOnTutorial()) {
            $entryBundleExtensionModules = $entry[3] ?? [];
            $bundleExtensionHTMLItems = $this->getExtensionHTMLItems($entryBundleExtensionModules);
            $messageBundleExtensionPlaceholder = sprintf(
                '<hr/><em>%s</em>',
                count($entryExtensionModules) === 1
                    ? \__('(It is included in %s)', 'gatographql')
                    : \__('(They are all included in %s)', 'gatographql')
            );
            $messageBundleExtensionContent = sprintf(
                $messageBundleExtensionPlaceholder,
                implode(
                    \__(', ', 'gatographql'),
                    $bundleExtensionHTMLItems
                )
            );
        }

        $messageHTML = sprintf(
            \__('<strong>🔗 %s</strong>: %s', 'gatographql'),
            \__('Extensions referenced in this tutorial lesson', 'gatographql'),
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
                    $messageBundleExtensionContent
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
     * @return array<array{0:string,1:string,2?:string[],3?:string[]}> Value: [0] => tutorial lesson file slug, [1] => title, [2] => array of extension modules, [3] => array of bundle modules
     */
    protected function getEntries(): array
    {
        $bundleExtensionAggregator = $this->getBundleExtensionAggregator();
        $entries = [];
        foreach ($this->getTutorialLessonDataProvider()->getTutorialLessonSlugDataItems() as $tutorialLessonSlug => $tutorialLessonDataItem) {
            /** @var string */
            $tutorialLessonTitle = $tutorialLessonDataItem[0];
            /** @var string[] */
            $tutorialLessonExtensionModules = $tutorialLessonDataItem[1] ?? [];
            $tutorialLessonBundleModules = $tutorialLessonExtensionModules === [] ? [] : $bundleExtensionAggregator->getBundleModulesComprisingAllExtensionModules($tutorialLessonExtensionModules);
            $entries[] = [
                $tutorialLessonSlug,
                $tutorialLessonTitle,
                $tutorialLessonExtensionModules,
                $tutorialLessonBundleModules,
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
