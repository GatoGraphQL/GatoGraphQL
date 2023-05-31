<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\App;
use GatoGraphQL\GatoGraphQL\Constants\HTMLCodes;
use GatoGraphQL\GatoGraphQL\Constants\RequestParams;
use GatoGraphQL\GatoGraphQL\ContentProcessors\ContentParserOptions;
use GatoGraphQL\GatoGraphQL\ContentProcessors\NoDocsFolderPluginMarkdownContentRetrieverTrait;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions\ExtensionModuleResolver;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions\ExtensionModuleResolverInterface;
use GatoGraphQL\GatoGraphQL\Registries\ModuleRegistryInterface;
use GatoGraphQL\GatoGraphQL\Services\MenuPages\AbstractDocsMenuPage;
use GatoGraphQL\GatoGraphQL\Services\MenuPages\OpenInModalTriggerMenuPageTrait;

class RecipesMenuPage extends AbstractDocsMenuPage
{
    use OpenInModalTriggerMenuPageTrait;
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
        return 'recipes';
    }

    protected function useTabpanelForContent(): bool
    {
        return false;
    }

    protected function getContentToPrint(): string
    {
        $recipeEntries = $this->getRecipeEntries();
        // By default, focus on the first recipe
        $activeRecipeName = $recipeEntries[0][0];
        // If passing a tab, focus on that one, if the module exists
        $tab = App::query(RequestParams::TAB);
        if ($tab !== null) {
            $recipeNames = array_map(
                fn (array $recipeEntry) => $recipeEntry[0],
                $recipeEntries
            );
            if (in_array($tab, $recipeNames)) {
                $activeRecipeName = $tab;
            }
        }
        $class = 'wrap vertical-tabs gato-graphql-tabpanel';

        $markdownContent = sprintf(
            <<<HTML
            <div id="%s" class="%s">
                <h1>%s</h1>
                <div class="nav-tab-container">
                    <!-- Tabs -->
                    <h2 class="nav-tab-wrapper">
            HTML,
            'gato-graphql-recipes',
            $class,
            \__('Gato GraphQL - Recipes: Use Cases, Best Practices, and Useful Queries', 'gato-graphql')
        );

        // This page URL
        $url = admin_url(sprintf(
            'admin.php?page=%s',
            esc_attr(App::request('page') ?? App::query('page', ''))
        ));

        foreach ($recipeEntries as $i => $recipeEntry) {
            $recipeEntryName = $recipeEntry[0];
            $recipeEntryTitle = $recipeEntry[1];

            // Enumerate the recipes
            $recipeEntryTitle = sprintf(
                \__('%s. %s', 'gato-graphql'),
                $i + 1,
                $recipeEntryTitle
            );

            /**
             * Also add the tab to the URL, not because it is needed,
             * but because we can then "Open in new tab" and it will
             * be focused already on that item.
             */
            $recipeURL = sprintf(
                '%1$s&%2$s=%3$s',
                $url,
                RequestParams::TAB,
                $recipeEntryName
            );
            $markdownContent .= sprintf(
                '<a data-tab-target="%s" href="%s" class="nav-tab %s">%s</a>',
                '#' . $recipeEntryName,
                $recipeURL,
                $recipeEntryName === $activeRecipeName ? 'nav-tab-active' : '',
                $recipeEntryTitle
            );
        }

        $markdownContent .= <<<HTML
                    </h2>
                    <div class="nav-tab-content">
        HTML;

        foreach ($recipeEntries as $recipeEntry) {
            $recipeEntryName = $recipeEntry[0];
            $recipeEntryTitle = $recipeEntry[1];
            $recipeEntryExtensionModules = $recipeEntry[2] ?? [];

            $recipeEntryRelativePathDir = 'docs/recipes';
            $recipeContent = $this->getMarkdownContent(
                $recipeEntryName,
                $recipeEntryRelativePathDir,
                [
                    ContentParserOptions::TAB_CONTENT => false,
                ]
            ) ?? sprintf(
                '<p>%s</p>',
                sprintf(
                    \__('Oops, there was a problem loading recipe "%s"', 'gato-graphql'),
                    $recipeEntryTitle
                )
            );

            // Hide the title from the content, as it's already shown below
            $recipeContent = str_replace(
                '<h1>',
                '<h1 style="display: none;">',
                $recipeContent
            );

            $markdownContent .= sprintf(
                <<<HTML
                    <div id="%s" class="%s" style="%s">
                        <h2>%s</h2><hr/>
                        %s
                    </div>
                HTML,
                $recipeEntryName,
                'tab-content',
                sprintf(
                    'display: %s;',
                    $recipeEntryName === $activeRecipeName ? 'block' : 'none'
                ),
                $recipeEntryTitle,
                $this->getRecipeContent(
                    $recipeContent,
                    $recipeEntryExtensionModules,
                )
            );
        }

        $markdownContent .= <<<HTML
                </div> <!-- class="nav-tab-content" -->
            </div> <!-- class="nav-tab-container" -->
        </div>
        HTML;
        return $markdownContent;
    }

    /**
     * @param string[] $recipeEntryExtensionModules
     */
    protected function getRecipeContent(
        string $recipeContent,
        array $recipeEntryExtensionModules,
    ): string {
        if ($recipeEntryExtensionModules === []) {
            return $recipeContent;
        }
        $messagePlaceholder = count($recipeEntryExtensionModules) === 1
            ? \__('%s This recipe requires extension %s to be active.', 'gato-graphql')
            : \__('%s This recipe requires extensions %s to be active.', 'gato-graphql');
        $extensionHTMLItems = [];
        foreach ($recipeEntryExtensionModules as $recipeEntryExtensionModule) {
            /** @var ExtensionModuleResolverInterface */
            $extensionModuleResolver = $this->getModuleRegistry()->getModuleResolver($recipeEntryExtensionModule);
            $extensionHTMLItems[] = sprintf(
                \__('<strong><a href="%s" target="%s">%s%s</a></strong>', 'gato-graphql'),
                $extensionModuleResolver->getWebsiteURL($recipeEntryExtensionModule),
                '_blank',
                $extensionModuleResolver->getName($recipeEntryExtensionModule),
                HTMLCodes::OPEN_IN_NEW_WINDOW
            );
        }
        $messageHTML = sprintf(
            $messagePlaceholder,
            '🌀',
            implode(
                \__(', ', 'gato-graphql'),
                $extensionHTMLItems
            )
        );
        return sprintf(
            <<<HTML
                <div class="%s">
                    <p>%s</p>
                </div>
            HTML,
            'extension-highlight',
            $messageHTML,
        ) . $recipeContent;
    }

    /**
     * Enqueue the required assets and initialize the localized scripts
     */
    protected function enqueueAssets(): void
    {
        parent::enqueueAssets();

        $this->enqueueTabpanelAssets();
    }

    /**
     * @return array<array{0:string,1:string,2?:string[]}>
     */
    protected function getRecipeEntries(): array
    {
        return [
            [
                'intro',
                \__('Intro', 'gato-graphql'),
            ],
            [
                'searching-wordpress-data',
                \__('Searching WordPress data', 'gato-graphql'),
            ],
            [
                'exposing-the-single-endpoint-for-private-use',
                \__('Exposing the single endpoint for private use', 'gato-graphql'),
            ],
            [
                'fetching-data-to-build-headless-sites',
                \__('Fetching data to build headless sites', 'gato-graphql'),
            ],
            [
                'complementing-wp-cli',
                \__('Complementing WP-CLI', 'gato-graphql'),
            ],
            [
                'exposing-safe-persisted-queries',
                \__('Exposing safe persisted queries', 'gato-graphql'),
            ],
            [
                'feeding-data-to-blocks-in-the-editor',
                \__('Feeding data to blocks in the editor', 'gato-graphql'),
            ],
            [
                'executing-graphql-queries-internally',
                \__('Executing GraphQL queries internally', 'gato-graphql'),
            ],
            [
                'defining-custom-private-endpoints',
                \__('Defining custom private endpoints', 'gato-graphql'),
            ],
            [
                'exposing-a-secure-public-api',
                \__('Exposing a secure public API', 'gato-graphql'),
            ],
            [
                'customizing-content-for-different-users',
                \__('Customizing content for different users', 'gato-graphql'),
            ],
            [
                'boosting-the-performance-of-the-api',
                \__('Boosting the performance of the API', 'gato-graphql'),
            ],
            [
                'site-migrations',
                \__('Site migrations', 'gato-graphql'),
                [
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ],
            ],
            [
                'fixing-content-issues',
                \__('Fixing content issues', 'gato-graphql'),
            ],
            [
                'inserting-a-gutenberg-block-in-all-posts',
                \__('Inserting a Gutenberg block in all posts', 'gato-graphql'),
                [
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ],
            ],
            [
                'removing-a-gutenberg-block-from-all-posts',
                \__('Removing a Gutenberg block from all posts', 'gato-graphql'),
            ],
            // [
            //     'converting-content-to-gutenberg-blocks',
            //      \__('Converting content to Gutenberg blocks', 'gato-graphql'),
            //     true,
            // ],
            [
                'persisted-queries-as-webhooks',
                \__('Persisted Queries as Webhooks', 'gato-graphql'),
            ],
            [
                'automating-tasks',
                \__('Automating tasks', 'gato-graphql'),
            ],
            [
                'executing-admin-tasks',
                \__('Executing admin tasks', 'gato-graphql'),
            ],
            [
                'bulk-editing-content',
                \__('Bulk editing content', 'gato-graphql'),
            ],
            [
                'interacting-with-3rd-party-service-apis',
                \__('Interacting with 3rd-party service APIs', 'gato-graphql'),
            ],
            [
                'creating-an-api-gateway',
                \__('Creating an API gateway', 'gato-graphql'),
            ],
            [
                'filtering-data-from-an-external-api',
                \__('Filtering data from an external API', 'gato-graphql'),
            ],
            [
                'transforming-data-from-an-external-api',
                \__('Transforming data from an external API', 'gato-graphql'),
            ],
            [
                'translating-all-posts-to-a-different-language',
                \__('Translating all posts to a different language', 'gato-graphql'),
                [
                    ExtensionModuleResolver::GOOGLE_TRANSLATE,
                ],
            ],
            [
                'combining-user-data-from-different-systems',
                \__('Combining user data from different systems', 'gato-graphql'),
            ],
            [
                'importing-a-post-from-another-site',
                \__('Importing a post from another site', 'gato-graphql'),
            ],
            [
                'importing-multiple-posts-at-once-from-another-site',
                \__('Importing multiple posts at once from another site', 'gato-graphql'),
            ],
            [
                'synchronizing-content-across-wordpress-sites',
                \__('Synchronizing content across WordPress sites', 'gato-graphql'),
            ],
            [
                'retrieving-and-downloading-github-artifacts',
                \__('Retrieving and downloading GitHub Artifacts', 'gato-graphql'),
            ],
            [
                'producing-an-error-if-the-request-entry-does-not-exist',
                \__('Producing an error if the requested entry does not exist', 'gato-graphql'),
            ],
            [
                'reverting-mutations-in-case-of-error',
                \__('Reverting mutations in case of error', 'gato-graphql'),
            ],
            [
                'content-orchestration',
                \__('Content orchestration', 'gato-graphql'),
            ],
            // [
            //     'using-the-graphql-server-without-wordpress',
            //      \__('Using the GraphQL server without WordPress', 'gato-graphql'),
            //     true,
            // ],
        ];
    }
}
