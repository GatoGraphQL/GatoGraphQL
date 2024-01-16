<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\DataProviders;

use GatoGraphQL\GatoGraphQL\Constants\TutorialLessons;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions\ExtensionModuleResolver;

class TutorialLessonDataProvider
{
    /**
     * @return array<string,array{0:string,1?:string[],2?:string[]}> Key: tutorial lesson file slug, Value: [0] => title, [1] => array of extension modules
     */
    public function getTutorialLessonSlugDataItems(): array
    {
        return [
            TutorialLessons::INTRO => [
                \__('Intro', 'gatographql'),
            ],
            TutorialLessons::SEARCHING_WORDPRESS_DATA => [
                \__('Searching WordPress data', 'gatographql'),
            ],
            TutorialLessons::QUERYING_DYNAMIC_DATA => [
                \__('Querying dynamic data', 'gatographql'),
                [
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::PHP_CONSTANTS_AND_ENVIRONMENT_VARIABLES_VIA_SCHEMA,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],
            // TutorialLessons::EXPOSING_PUBLIC_AND_PRIVATE_ENDPOINTS => [
            //     \__('Exposing public and private endpoints', 'gatographql'),
            //     [
            //         ExtensionModuleResolver::ACCESS_CONTROL,
            //         ExtensionModuleResolver::ACCESS_CONTROL_VISITOR_IP,
            //         ExtensionModuleResolver::CACHE_CONTROL,
            //     ]
            // ],
            TutorialLessons::COMPLEMENTING_WP_CLI => [
                \__('Complementing WP-CLI', 'gatographql')
            ],
            // TutorialLessons::INJECTING_MULTIPLE_RESOURCES_INTO_WP_CLI => [
            //     \__('Injecting multiple resources into WP-CLI', 'gatographql'),
            //     [
            //         ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
            //         ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
            //     ]
            // ],
            // TutorialLessons::FEEDING_DATA_TO_BLOCKS_IN_THE_EDITOR => [
            //     \__('Feeding data to blocks in the editor', 'gatographql'),
            // ],
            // TutorialLessons::DRY_CODE_FOR_BLOCKS_IN_JAVASCRIPT_AND_PHP => [
            //     \__('DRY code for blocks in Javascript and PHP', 'gatographql'),
            //     [
            //         ExtensionModuleResolver::INTERNAL_GRAPHQL_SERVER,
            //     ]
            // ],
            // TutorialLessons::MAPPING_JS_COMPONENTS_TO_GUTENBERG_BLOCKS => [
            //     \__('Mapping JS components to (Gutenberg) blocks', 'gatographql'),
            // ],
            TutorialLessons::DUPLICATING_A_BLOG_POST => [
                \__('Duplicating a blog post', 'gatographql'),
                [
                    ExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],
            TutorialLessons::DUPLICATING_MULTIPLE_BLOG_POSTS_AT_ONCE => [
                \__('Duplicating multiple blog posts at once', 'gatographql'),
                [
                    ExtensionModuleResolver::FIELD_ON_FIELD,
                    ExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],
            TutorialLessons::CUSTOMIZING_CONTENT_FOR_DIFFERENT_USERS => [
                \__('Customizing content for different users', 'gatographql'),
                [
                    ExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],
            TutorialLessons::SEARCH_REPLACE_AND_STORE_AGAIN => [
                \__('Search, replace, and store again', 'gatographql'),
                [
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],
            TutorialLessons::ADAPTING_CONTENT_IN_BULK => [
                \__('Adapting content in bulk', 'gatographql'),
                [
                    ExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],
            TutorialLessons::SITE_MIGRATIONS => [
                \__('Site migrations', 'gatographql'),
                [
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],
            TutorialLessons::INSERTING_REMOVING_A_GUTENBERG_BLOCK_IN_BULK => [
                \__('Inserting/Removing a (Gutenberg) block in bulk', 'gatographql'),
                [
                    ExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],
            TutorialLessons::RETRIEVING_STRUCTURED_DATA_FROM_BLOCKS => [
                \__('Retrieving structured data from blocks', 'gatographql'),
                [
                    ExtensionModuleResolver::FIELD_ON_FIELD,
                    ExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],
            TutorialLessons::MODIFYING_AND_STORING_AGAIN_THE_IMAGE_URLS_FROM_ALL_IMAGE_BLOCKS_IN_A_POST => [
                \__('Modifying (and storing again) the image URLs from all Image blocks in a post', 'gatographql'),
                [
                    ExtensionModuleResolver::FIELD_ON_FIELD,
                    ExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],
            TutorialLessons::TRANSLATING_BLOCK_CONTENT_IN_A_POST_TO_A_DIFFERENT_LANGUAGE => [
                \__('Translating block content in a post to a different language', 'gatographql'),
                [
                    ExtensionModuleResolver::FIELD_ON_FIELD,
                    ExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                    ExtensionModuleResolver::GOOGLE_TRANSLATE,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],
            TutorialLessons::BULK_TRANSLATING_BLOCK_CONTENT_IN_MULTIPLE_POSTS_TO_A_DIFFERENT_LANGUAGE => [
                \__('Bulk translating block content in multiple posts to a different language', 'gatographql'),
                [
                    ExtensionModuleResolver::CONDITIONAL_FIELD_MANIPULATION,
                    ExtensionModuleResolver::FIELD_ON_FIELD,
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                    ExtensionModuleResolver::GOOGLE_TRANSLATE,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],
            TutorialLessons::SENDING_EMAILS_WITH_PLEASURE => [
                \__('Sending emails with pleasure', 'gatographql'),
                [
                    ExtensionModuleResolver::EMAIL_SENDER,
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::HELPER_FUNCTION_COLLECTION,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],
            TutorialLessons::SENDING_A_NOTIFICATION_WHEN_THERE_IS_A_NEW_POST => [
                \__('Sending a notification when there is a new post', 'gatographql'),
                [
                    ExtensionModuleResolver::AUTOMATION,
                    ExtensionModuleResolver::EMAIL_SENDER,
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::HELPER_FUNCTION_COLLECTION,
                    ExtensionModuleResolver::INTERNAL_GRAPHQL_SERVER,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],
            TutorialLessons::SENDING_A_DAILY_SUMMARY_OF_ACTIVITY => [
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
                ]
            ],
            TutorialLessons::AUTOMATICALLY_ADDING_A_MANDATORY_BLOCK => [
                \__('Automatically adding a mandatory block', 'gatographql'),
                [
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::INTERNAL_GRAPHQL_SERVER,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],
            TutorialLessons::INTERACTING_WITH_EXTERNAL_SERVICES_VIA_WEBHOOKS => [
                \__('Interacting with external services via webhooks', 'gatographql'),
                [
                    ExtensionModuleResolver::EMAIL_SENDER,
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::HTTP_REQUEST_VIA_SCHEMA,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],
            TutorialLessons::RETRIEVING_DATA_FROM_AN_EXTERNAL_API => [
                \__('Retrieving data from an external API', 'gatographql'),
                [
                    ExtensionModuleResolver::FIELD_RESOLUTION_CACHING,
                    ExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                    ExtensionModuleResolver::HTTP_CLIENT,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],
            TutorialLessons::COMBINING_USER_DATA_FROM_DIFFERENT_SOURCES => [
                \__('Combining user data from different sources', 'gatographql'),
                [
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],
            TutorialLessons::NOT_LEAKING_CREDENTIALS_WHEN_CONNECTING_TO_SERVICES => [
                \__('Not leaking credentials when connecting to services', 'gatographql'),
                [
                    ExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::HTTP_CLIENT,
                    ExtensionModuleResolver::PHP_CONSTANTS_AND_ENVIRONMENT_VARIABLES_VIA_SCHEMA,
                ]
            ],
            TutorialLessons::HANDLING_ERRORS_WHEN_CONNECTING_TO_SERVICES => [
                \__('Handling errors when connecting to services', 'gatographql'),
                [
                    ExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::HTTP_CLIENT,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                    ExtensionModuleResolver::RESPONSE_ERROR_TRIGGER,
                ]
            ],
            TutorialLessons::CREATING_AN_API_GATEWAY => [
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
                ]
            ],
            TutorialLessons::TRANSLATING_CONTENT_FROM_URL => [
                \__('Translating content from URL', 'gatographql'),
                [
                    ExtensionModuleResolver::GOOGLE_TRANSLATE,
                    ExtensionModuleResolver::HTTP_CLIENT,
                ]
            ],
            TutorialLessons::TRANSFORMING_DATA_FROM_AN_EXTERNAL_API => [
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
                ]
            ],
            TutorialLessons::FILTERING_DATA_FROM_AN_EXTERNAL_API => [
                \__('Filtering data from an external API', 'gatographql'),
                [
                    ExtensionModuleResolver::CONDITIONAL_FIELD_MANIPULATION,
                    ExtensionModuleResolver::FIELD_ON_FIELD,
                    ExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                    ExtensionModuleResolver::HTTP_CLIENT,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],
            TutorialLessons::PINGING_EXTERNAL_SERVICES => [
                \__('Pinging external services', 'gatographql'),
                [
                    ExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::HTTP_CLIENT,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],
            TutorialLessons::UPDATING_LARGE_SETS_OF_DATA => [
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
                ]
            ],
            TutorialLessons::IMPORTING_A_POST_FROM_ANOTHER_WORDPRESS_SITE => [
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
                ]
            ],
            TutorialLessons::DISTRIBUTING_CONTENT_FROM_AN_UPSTREAM_TO_MULTIPLE_DOWNSTREAM_SITES => [
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
                ]
            ],
            TutorialLessons::AUTOMATICALLY_SENDING_NEWSLETTER_SUBSCRIBERS_FROM_INSTAWP_TO_MAILCHIMP => [
                \__('Automatically sending newsletter subscribers from InstaWP to Mailchimp', 'gatographql'),
                [
                    ExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::HTTP_CLIENT,
                    ExtensionModuleResolver::HTTP_REQUEST_VIA_SCHEMA,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_CONSTANTS_AND_ENVIRONMENT_VARIABLES_VIA_SCHEMA,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],
            // 'content-orchestration' => [
            //     \__('Content orchestration', 'gatographql'),
            // ],
            // 'using-the-graphql-server-without-wordpress' => [
            //     \__('Using the GraphQL server without WordPress', 'gatographql'),
            // ],
        ];
    }
}
