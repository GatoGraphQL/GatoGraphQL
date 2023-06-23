<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\Constants\HTMLCodes;
use GatoGraphQL\GatoGraphQL\ContentProcessors\NoDocsFolderPluginMarkdownContentRetrieverTrait;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions\ExtensionModuleResolver;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions\ExtensionModuleResolverInterface;
use GatoGraphQL\GatoGraphQL\Registries\ModuleRegistryInterface;

class RecipesMenuPage extends AbstractVerticalTabDocsMenuPage
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
        return 'recipes';
    }

    protected function getPageTitle(): string
    {
        return \__('Gato GraphQL - Entries: Use Cases, Best Practices, and Useful Queries', 'gato-graphql');
    }

    protected function getContentID(): string
    {
        return 'gato-graphql-recipes';
    }

    /**
     * @param array{0:string,1:string} $entry
     */
    protected function getEntryRelativePathDir(array $entry): string
    {
        return 'docs/recipes';
    }

    protected function enumerateEntries(): bool
    {
        return true;
    }

    /**
     * @param array{0:string,1:string,2?:string[]} $entry
     */
    protected function getEntryContent(
        string $entryContent,
        array $entry,
    ): string {
        $entryExtensionModules = $entry[2] ?? [];
        if ($entryExtensionModules === []) {
            return $entryContent;
        }
        $messagePlaceholder = count($entryExtensionModules) === 1
            ? \__('%s This recipe requires extension %s.', 'gato-graphql')
            : \__('%s This recipe requires extensions %s.', 'gato-graphql');
        $extensionHTMLItems = [];
        foreach ($entryExtensionModules as $entryExtensionModule) {
            /** @var ExtensionModuleResolverInterface */
            $extensionModuleResolver = $this->getModuleRegistry()->getModuleResolver($entryExtensionModule);
            $extensionHTMLItems[] = sprintf(
                \__('<strong><a href="%s" target="%s">%s%s</a></strong>', 'gato-graphql'),
                $extensionModuleResolver->getWebsiteURL($entryExtensionModule),
                '_blank',
                $extensionModuleResolver->getName($entryExtensionModule),
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
        ) . $entryContent;
    }

    /**
     * @return array<array{0:string,1:string,2?:string[]}>
     */
    protected function getEntries(): array
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
            // ],
        ];
    }
}
