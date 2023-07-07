<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\Constants\HTMLCodes;
use GatoGraphQL\GatoGraphQL\ContentProcessors\NoDocsFolderPluginMarkdownContentRetrieverTrait;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions\BundleExtensionModuleResolver;
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
        if ($this->moduleRegistry === null) {
            /** @var ModuleRegistryInterface */
            $moduleRegistry = $this->instanceManager->getInstance(ModuleRegistryInterface::class);
            $this->moduleRegistry = $moduleRegistry;
        }
        return $this->moduleRegistry;
    }

    public function getMenuPageSlug(): string
    {
        return 'recipes';
    }

    protected function getPageTitle(): string
    {
        return \__('Gato GraphQL - Recipes', 'gato-graphql');
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

    protected function hasCollapsibleContent(): bool
    {
        return true;
    }

    protected function getDocsFolder(): string
    {
        return '';
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

        $messageExtensionPlaceholder = \__('<ul><li>%s</li></ul>', 'gato-graphql');

        $extensionHTMLItems = $this->getExtensionHTMLItems($entryExtensionModules);

        $entryBundleExtensionModules = $entry[3] ?? [];
        $entryBundleExtensionModules[] = BundleExtensionModuleResolver::ALL_EXTENSIONS;
        $bundleExtensionHTMLItems = $this->getExtensionHTMLItems($entryBundleExtensionModules);
        $messageBundleExtensionPlaceholder = sprintf(
            '<hr/><em>%s</em>',
            count($entryExtensionModules) === 1
                ? \__('(It is included in %s)', 'gato-graphql')
                : \__('(They are all included in %s)', 'gato-graphql')
        );

        $messageHTML = sprintf(
            \__('<strong>🔗 %s</strong>: %s', 'gato-graphql'),
            \__('Extensions referenced in this recipe', 'gato-graphql'),
            $this->getCollapsible(
                sprintf(
                    '%s%s',
                    sprintf(
                        $messageExtensionPlaceholder,
                        implode(
                            \__('</li><li>', 'gato-graphql'),
                            $extensionHTMLItems
                        )
                    ),
                    sprintf(
                        $messageBundleExtensionPlaceholder,
                        implode(
                            \__(', ', 'gato-graphql'),
                            $bundleExtensionHTMLItems
                        )
                    )
                )
            )
        );

        return sprintf(
            <<<HTML
                <div class="%s">
                    %s
                </div>
            HTML,
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
                \__('<strong><a href="%s" target="%s">%s%s</a></strong>', 'gato-graphql'),
                $extensionModuleResolver->getWebsiteURL($entryExtensionModule),
                '_blank',
                $extensionModuleResolver->getName($entryExtensionModule),
                HTMLCodes::OPEN_IN_NEW_WINDOW
            );
        }
        return $extensionHTMLItems;
    }

    /**
     * @return array<array{0:string,1:string,2?:string[],3?:string[]}>
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
                [
                    ExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::PHP_CONSTANTS_AND_ENVIRONMENT_VARIABLES_VIA_SCHEMA,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ],
                [
                    BundleExtensionModuleResolver::APPLICATION_GLUE_AND_AUTOMATOR,
                ]
            ],
            [
                'complementing-wp-cli',
                \__('Complementing WP-CLI', 'gato-graphql'),
                [
                    ExtensionModuleResolver::ACCESS_CONTROL,
                    ExtensionModuleResolver::ACCESS_CONTROL_VISITOR_IP,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ],
            ],
            [
                'feeding-data-to-blocks-in-the-editor',
                \__('Feeding data to blocks in the editor', 'gato-graphql'),
            ],
            [
                'dry-code-for-blocks-in-javascript-and-php',
                \__('DRY code for blocks in Javascript and PHP', 'gato-graphql'),
                [
                    ExtensionModuleResolver::INTERNAL_GRAPHQL_SERVER,
                ],
                [
                    BundleExtensionModuleResolver::APPLICATION_GLUE_AND_AUTOMATOR,
                ]
            ],
            [
                'duplicating-a-blog-post',
                \__('Duplicating a blog post', 'gato-graphql'),
                [
                    ExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ],
                [
                    BundleExtensionModuleResolver::APPLICATION_GLUE_AND_AUTOMATOR,
                ]
            ],
            [
                'duplicating-multiple-blog-posts-at-once',
                \__('Duplicating multiple blog posts at once', 'gato-graphql'),
            ],
            [
                'customizing-content-for-different-users',
                \__('Customizing content for different users', 'gato-graphql'),
            ],
            [
                'sending-an-email-using-markdown',
                \__('Sending an email using Markdown', 'gato-graphql'),
            ],
            [
                'site-migrations',
                \__('Site migrations', 'gato-graphql'),
                [
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ],
                [
                    BundleExtensionModuleResolver::APPLICATION_GLUE_AND_AUTOMATOR,
                ]
            ],
            [
                'content-search-and-replace',
                \__('Content search and replace', 'gato-graphql'),
            ],
            [
                'adapting-content',
                \__('Adapting content', 'gato-graphql'),
            ],
            [
                'validating-that-a-new-post-contains-a-mandatory-block',
                \__('Validating that a new post contains a mandatory block', 'gato-graphql')
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
                'sending-a-notification-when-some-event-happens',
                \__('Sending a notification when some event happens', 'gato-graphql'),
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
                'translating-block-content-in-a-post-to-a-different-language',
                \__('Translating block content in a post to a different language', 'gato-graphql'),
                [
                    ExtensionModuleResolver::GOOGLE_TRANSLATE,
                ],
                [
                    BundleExtensionModuleResolver::CONTENT_TRANSLATION,
                ]
            ],
            [
                'bulk-translating-block-content-in-multiple-posts-to-a-different-language',
                \__('Bulk translating block content in multiple posts to a different language', 'gato-graphql'),
                [
                    ExtensionModuleResolver::GOOGLE_TRANSLATE,
                ],
                [
                    BundleExtensionModuleResolver::CONTENT_TRANSLATION,
                ]
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
