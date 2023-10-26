<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\Constants\HTMLCodes;
use GatoGraphQL\GatoGraphQL\ContentProcessors\ContentParserOptions;
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
     * @return array<array{0:string,1:string,2?:string[],3?:string[]}>
     */
    protected function getEntries(): array
    {
        return [
            [
                'intro',
                \__('Intro', 'gatographql'),
            ],
            [
                'searching-wordpress-data',
                \__('Searching WordPress data', 'gatographql'),
            ],
            [
                'querying-dynamic-data',
                \__('Querying dynamic data', 'gatographql'),
                [
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::PHP_CONSTANTS_AND_ENVIRONMENT_VARIABLES_VIA_SCHEMA,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ],
                [
                    BundleExtensionModuleResolver::APPLICATION_GLUE_AND_AUTOMATOR,
                ]
            ],
            [
                'exposing-public-and-private-endpoints',
                \__('Exposing public and private endpoints', 'gatographql'),
                [
                    ExtensionModuleResolver::ACCESS_CONTROL,
                    ExtensionModuleResolver::ACCESS_CONTROL_VISITOR_IP,
                    ExtensionModuleResolver::CACHE_CONTROL,
                ],
                [
                    BundleExtensionModuleResolver::PUBLIC_API,
                ]
            ],
            [
                'complementing-wp-cli',
                \__('Complementing WP-CLI', 'gatographql')
            ],
            [
                'injecting-multiple-resources-into-wp-cli',
                \__('Injecting multiple resources into WP-CLI', 'gatographql'),
                [
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ],
                [
                    BundleExtensionModuleResolver::APPLICATION_GLUE_AND_AUTOMATOR,
                    BundleExtensionModuleResolver::CONTENT_TRANSLATION,
                ]
            ],
            [
                'feeding-data-to-blocks-in-the-editor',
                \__('Feeding data to blocks in the editor', 'gatographql'),
            ],
            [
                'dry-code-for-blocks-in-javascript-and-php',
                \__('DRY code for blocks in Javascript and PHP', 'gatographql'),
                [
                    ExtensionModuleResolver::INTERNAL_GRAPHQL_SERVER,
                ],
                [
                    BundleExtensionModuleResolver::APPLICATION_GLUE_AND_AUTOMATOR,
                ]
            ],
            [
                'mapping-js-components-to-gutenberg-blocks',
                \__('Mapping JS components to (Gutenberg) blocks', 'gatographql'),
            ],
            [
                'duplicating-a-blog-post',
                \__('Duplicating a blog post', 'gatographql'),
                [
                    ExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ],
                [
                    BundleExtensionModuleResolver::APPLICATION_GLUE_AND_AUTOMATOR,
                    BundleExtensionModuleResolver::CONTENT_TRANSLATION,
                ]
            ],
            [
                'duplicating-multiple-blog-posts-at-once',
                \__('Duplicating multiple blog posts at once', 'gatographql'),
                [
                    ExtensionModuleResolver::FIELD_ON_FIELD,
                    ExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ],
                [
                    BundleExtensionModuleResolver::APPLICATION_GLUE_AND_AUTOMATOR,
                    BundleExtensionModuleResolver::CONTENT_TRANSLATION,
                ]
            ],
            [
                'customizing-content-for-different-users',
                \__('Customizing content for different users', 'gatographql'),
                [
                    ExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ],
                [
                    BundleExtensionModuleResolver::APPLICATION_GLUE_AND_AUTOMATOR,
                    BundleExtensionModuleResolver::CONTENT_TRANSLATION,
                ]
            ],
            [
                'search-replace-and-store-again',
                \__('Search, replace, and store again', 'gatographql'),
                [
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ],
                [
                    BundleExtensionModuleResolver::APPLICATION_GLUE_AND_AUTOMATOR,
                    BundleExtensionModuleResolver::CONTENT_TRANSLATION,
                ]
            ],
            [
                'adapting-content-in-bulk',
                \__('Adapting content in bulk', 'gatographql'),
                [
                    ExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ],
                [
                    BundleExtensionModuleResolver::APPLICATION_GLUE_AND_AUTOMATOR,
                    BundleExtensionModuleResolver::CONTENT_TRANSLATION,
                ]
            ],
            [
                'site-migrations',
                \__('Site migrations', 'gatographql'),
                [
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ],
                [
                    BundleExtensionModuleResolver::APPLICATION_GLUE_AND_AUTOMATOR,
                    BundleExtensionModuleResolver::CONTENT_TRANSLATION,
                ]
            ],
            [
                'inserting-removing-a-gutenberg-block-in-bulk',
                \__('Inserting/Removing a (Gutenberg) block in bulk', 'gatographql'),
                [
                    ExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ],
                [
                    BundleExtensionModuleResolver::APPLICATION_GLUE_AND_AUTOMATOR,
                    BundleExtensionModuleResolver::CONTENT_TRANSLATION,
                ]
            ],
            [
                'retrieving-structured-data-from-blocks',
                \__('Retrieving structured data from blocks', 'gatographql'),
                [
                    ExtensionModuleResolver::FIELD_ON_FIELD,
                    ExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ],
                [
                    BundleExtensionModuleResolver::APPLICATION_GLUE_AND_AUTOMATOR,
                    BundleExtensionModuleResolver::CONTENT_TRANSLATION,
                ]
            ],
            [
                'modifying-and-storing-again-the-image-urls-from-all-image-blocks-in-a-post',
                \__('Modifying (and storing again) the image URLs from all Image blocks in a post', 'gatographql'),
                [
                    ExtensionModuleResolver::FIELD_ON_FIELD,
                    ExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ],
                [
                    BundleExtensionModuleResolver::APPLICATION_GLUE_AND_AUTOMATOR,
                    BundleExtensionModuleResolver::CONTENT_TRANSLATION,
                ]
            ],
            [
                'translating-block-content-in-a-post-to-a-different-language',
                \__('Translating block content in a post to a different language', 'gatographql'),
                [
                    ExtensionModuleResolver::FIELD_ON_FIELD,
                    ExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                    ExtensionModuleResolver::GOOGLE_TRANSLATE,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ],
                [
                    BundleExtensionModuleResolver::CONTENT_TRANSLATION,
                ]
            ],
            [
                'bulk-translating-block-content-in-multiple-posts-to-a-different-language',
                \__('Bulk translating block content in multiple posts to a different language', 'gatographql'),
                [
                    ExtensionModuleResolver::CONDITIONAL_FIELD_MANIPULATION,
                    ExtensionModuleResolver::FIELD_ON_FIELD,
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                    ExtensionModuleResolver::GOOGLE_TRANSLATE,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ],
                [
                    BundleExtensionModuleResolver::CONTENT_TRANSLATION,
                ]
            ],
            [
                'sending-emails-with-pleasure',
                \__('Sending emails with pleasure', 'gatographql'),
                [
                    ExtensionModuleResolver::EMAIL_SENDER,
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::HELPER_FUNCTION_COLLECTION,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ],
                [
                    BundleExtensionModuleResolver::APPLICATION_GLUE_AND_AUTOMATOR,
                ]
            ],
            [
                'sending-a-notification-when-there-is-a-new-post',
                \__('Sending a notification when there is a new post', 'gatographql'),
                [
                    ExtensionModuleResolver::AUTOMATION,
                    ExtensionModuleResolver::EMAIL_SENDER,
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::HELPER_FUNCTION_COLLECTION,
                    ExtensionModuleResolver::INTERNAL_GRAPHQL_SERVER,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ],
                [
                    BundleExtensionModuleResolver::APPLICATION_GLUE_AND_AUTOMATOR,
                ]
            ],
            [
                'sending-a-daily-summary-of-activity',
                \__('Sending a daily summary of activity', 'gatographql'),
                [
                    ExtensionModuleResolver::AUTOMATION,
                    ExtensionModuleResolver::EMAIL_SENDER,
                    ExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::HELPER_FUNCTION_COLLECTION,
                    ExtensionModuleResolver::INTERNAL_GRAPHQL_SERVER,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ],
                [
                    BundleExtensionModuleResolver::APPLICATION_GLUE_AND_AUTOMATOR,
                ]
            ],
            [
                'automatically-adding-a-mandatory-block',
                \__('Automatically adding a mandatory block', 'gatographql'),
                [
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::INTERNAL_GRAPHQL_SERVER,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ],
                [
                    BundleExtensionModuleResolver::APPLICATION_GLUE_AND_AUTOMATOR,
                ]
            ],
            [
                'interacting-with-external-services-via-webhooks',
                \__('Interacting with external services via webhooks', 'gatographql'),
                [
                    ExtensionModuleResolver::EMAIL_SENDER,
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::HTTP_REQUEST_VIA_SCHEMA,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ],
                [
                    BundleExtensionModuleResolver::APPLICATION_GLUE_AND_AUTOMATOR,
                ]
            ],
            [
                'retrieving-data-from-an-external-api',
                \__('Retrieving data from an external API', 'gatographql'),
                [
                    ExtensionModuleResolver::FIELD_RESOLUTION_CACHING,
                    ExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                    ExtensionModuleResolver::HTTP_CLIENT,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ],
                [
                    BundleExtensionModuleResolver::APPLICATION_GLUE_AND_AUTOMATOR,
                ]
            ],
            [
                'combining-user-data-from-different-sources',
                \__('Combining user data from different sources', 'gatographql'),
                [
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ],
                [
                    BundleExtensionModuleResolver::APPLICATION_GLUE_AND_AUTOMATOR,
                    BundleExtensionModuleResolver::CONTENT_TRANSLATION,
                ]
            ],
            [
                'not-leaking-credentials-when-connecting-to-services',
                \__('Not leaking credentials when connecting to services', 'gatographql'),
                [
                    ExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::HTTP_CLIENT,
                    ExtensionModuleResolver::PHP_CONSTANTS_AND_ENVIRONMENT_VARIABLES_VIA_SCHEMA,
                ],
                [
                    BundleExtensionModuleResolver::APPLICATION_GLUE_AND_AUTOMATOR,
                ]
            ],
            [
                'handling-errors-when-connecting-to-services',
                \__('Handling errors when connecting to services', 'gatographql'),
                [
                    ExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::HTTP_CLIENT,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                    ExtensionModuleResolver::RESPONSE_ERROR_TRIGGER,
                ],
                [
                    BundleExtensionModuleResolver::APPLICATION_GLUE_AND_AUTOMATOR,
                ]
            ],
            [
                'creating-an-api-gateway',
                \__('Creating an API gateway', 'gatographql'),
                [
                    ExtensionModuleResolver::FIELD_ON_FIELD,
                    ExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                    ExtensionModuleResolver::HTTP_CLIENT,
                    ExtensionModuleResolver::HTTP_REQUEST_VIA_SCHEMA,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_CONSTANTS_AND_ENVIRONMENT_VARIABLES_VIA_SCHEMA,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                    ExtensionModuleResolver::RESPONSE_ERROR_TRIGGER,
                ],
                [
                    BundleExtensionModuleResolver::APPLICATION_GLUE_AND_AUTOMATOR,
                ]
            ],
            [
                'transforming-data-from-an-external-api',
                \__('Transforming data from an external API', 'gatographql'),
                [
                    ExtensionModuleResolver::CONDITIONAL_FIELD_MANIPULATION,
                    ExtensionModuleResolver::FIELD_DEFAULT_VALUE,
                    ExtensionModuleResolver::FIELD_ON_FIELD,
                    ExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                    ExtensionModuleResolver::HTTP_CLIENT,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ],
                [
                    BundleExtensionModuleResolver::APPLICATION_GLUE_AND_AUTOMATOR,
                ]
            ],
            [
                'filtering-data-from-an-external-api',
                \__('Filtering data from an external API', 'gatographql'),
                [
                    ExtensionModuleResolver::CONDITIONAL_FIELD_MANIPULATION,
                    ExtensionModuleResolver::FIELD_ON_FIELD,
                    ExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                    ExtensionModuleResolver::HTTP_CLIENT,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ],
                [
                    BundleExtensionModuleResolver::APPLICATION_GLUE_AND_AUTOMATOR,
                ]
            ],
            [
                'pinging-external-services',
                \__('Pinging external services', 'gatographql'),
                [
                    ExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::HTTP_CLIENT,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ],
                [
                    BundleExtensionModuleResolver::APPLICATION_GLUE_AND_AUTOMATOR,
                ]
            ],
            [
                'updating-large-sets-of-data',
                \__('Updating large sets of data', 'gatographql'),
                [
                    ExtensionModuleResolver::FIELD_ON_FIELD,
                    ExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                    ExtensionModuleResolver::HTTP_CLIENT,
                    ExtensionModuleResolver::HTTP_REQUEST_VIA_SCHEMA,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ],
                [
                    BundleExtensionModuleResolver::APPLICATION_GLUE_AND_AUTOMATOR,
                ]
            ],
            [
                'importing-a-post-from-another-wordpress-site',
                \__('Importing a post from another WordPress site', 'gatographql'),
                [
                    ExtensionModuleResolver::CONDITIONAL_FIELD_MANIPULATION,
                    ExtensionModuleResolver::FIELD_ON_FIELD,
                    ExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                    ExtensionModuleResolver::HTTP_CLIENT,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                    ExtensionModuleResolver::RESPONSE_ERROR_TRIGGER,
                ],
                [
                    BundleExtensionModuleResolver::APPLICATION_GLUE_AND_AUTOMATOR,
                ]
            ],
            [
                'distributing-content-from-an-upstream-to-multiple-downstream-sites',
                \__('Distributing content from an upstream to multiple downstream sites', 'gatographql'),
                [
                    ExtensionModuleResolver::FIELD_ON_FIELD,
                    ExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                    ExtensionModuleResolver::HTTP_CLIENT,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                    ExtensionModuleResolver::RESPONSE_ERROR_TRIGGER,
                ],
                [
                    BundleExtensionModuleResolver::APPLICATION_GLUE_AND_AUTOMATOR,
                ]
            ],
            // [
            //     'content-orchestration',
            //     \__('Content orchestration', 'gatographql'),
            // ],
            // [
            //     'using-the-graphql-server-without-wordpress',
            //      \__('Using the GraphQL server without WordPress', 'gatographql'),
            // ],
        ];
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
