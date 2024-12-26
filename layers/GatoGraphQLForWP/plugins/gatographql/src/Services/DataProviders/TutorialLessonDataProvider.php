<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\DataProviders;

use GatoGraphQL\GatoGraphQL\Constants\TutorialLessons;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions\PowerExtensionModuleResolver;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions\PremiumExtensionModuleResolver;

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
                \__('Lesson 1: Searching WordPress data', 'gatographql'),
            ],
            TutorialLessons::QUERYING_DYNAMIC_DATA => [
                \__('Lesson 2: Querying dynamic data', 'gatographql'),
                [
                    PowerExtensionModuleResolver::FIELD_TO_INPUT,
                    PowerExtensionModuleResolver::PHP_CONSTANTS_AND_ENVIRONMENT_VARIABLES_VIA_SCHEMA,
                    PowerExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],
            // TutorialLessons::EXPOSING_PUBLIC_AND_PRIVATE_ENDPOINTS => [
            //     \__('Exposing public and private endpoints', 'gatographql'),
            //     [
            //         PowerExtensionModuleResolver::ACCESS_CONTROL,
            //         PowerExtensionModuleResolver::ACCESS_CONTROL_VISITOR_IP,
            //         PowerExtensionModuleResolver::CACHE_CONTROL,
            //     ]
            // ],
            // TutorialLessons::COMPLEMENTING_WP_CLI => [
            //     \__('Complementing WP-CLI', 'gatographql')
            // ],
            // TutorialLessons::INJECTING_MULTIPLE_RESOURCES_INTO_WP_CLI => [
            //     \__('Injecting multiple resources into WP-CLI', 'gatographql'),
            //     [
            //         PowerExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
            //         PowerExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
            //     ]
            // ],
            // TutorialLessons::FEEDING_DATA_TO_BLOCKS_IN_THE_EDITOR => [
            //     \__('Feeding data to blocks in the editor', 'gatographql'),
            // ],
            // TutorialLessons::DRY_CODE_FOR_BLOCKS_IN_JAVASCRIPT_AND_PHP => [
            //     \__('DRY code for blocks in Javascript and PHP', 'gatographql'),
            //     [
            //         PowerExtensionModuleResolver::INTERNAL_GRAPHQL_SERVER,
            //     ]
            // ],
            // TutorialLessons::MAPPING_JS_COMPONENTS_TO_GUTENBERG_BLOCKS => [
            //     \__('Mapping JS components to (Gutenberg) blocks', 'gatographql'),
            // ],
            TutorialLessons::DUPLICATING_A_BLOG_POST => [
                \__('Lesson 3: Duplicating a blog post', 'gatographql'),
                [
                    PowerExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                    PowerExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    PowerExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],
            TutorialLessons::DUPLICATING_MULTIPLE_BLOG_POSTS_AT_ONCE => [
                \__('Lesson 4: Duplicating multiple blog posts at once', 'gatographql'),
                [
                    PowerExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                    PowerExtensionModuleResolver::FIELD_TO_INPUT,
                    PowerExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    PowerExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],
            TutorialLessons::CUSTOMIZING_CONTENT_FOR_DIFFERENT_USERS => [
                \__('Lesson 5: Customizing content for different users', 'gatographql'),
                [
                    PowerExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                    PowerExtensionModuleResolver::FIELD_TO_INPUT,
                    PowerExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    PowerExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],
            TutorialLessons::SEARCH_REPLACE_AND_STORE_AGAIN => [
                \__('Lesson 6: Search, replace, and store again', 'gatographql'),
                [
                    PowerExtensionModuleResolver::FIELD_TO_INPUT,
                    PowerExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    PowerExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],
            TutorialLessons::ADAPTING_CONTENT_IN_BULK => [
                \__('Lesson 7: Adapting content in bulk', 'gatographql'),
                [
                    PowerExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                    PowerExtensionModuleResolver::FIELD_TO_INPUT,
                    PowerExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    PowerExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],
            TutorialLessons::SITE_MIGRATIONS => [
                \__('Lesson 8: Site migrations', 'gatographql'),
                [
                    PowerExtensionModuleResolver::FIELD_TO_INPUT,
                    PowerExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    PowerExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],
            TutorialLessons::INSERTING_REMOVING_A_GUTENBERG_BLOCK_IN_BULK => [
                \__('Lesson 9: Inserting/Removing a (Gutenberg) block in bulk', 'gatographql'),
                [
                    PowerExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                    PowerExtensionModuleResolver::FIELD_TO_INPUT,
                    PowerExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    PowerExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],
            TutorialLessons::RETRIEVING_STRUCTURED_DATA_FROM_BLOCKS => [
                \__('Lesson 10: Retrieving structured data from blocks', 'gatographql'),
                [
                    PowerExtensionModuleResolver::FIELD_ON_FIELD,
                    PowerExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                    PowerExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],
            TutorialLessons::MODIFYING_AND_STORING_AGAIN_THE_IMAGE_URLS_FROM_ALL_IMAGE_BLOCKS_IN_A_POST => [
                \__('Lesson 11: Modifying (and storing again) the image URLs from all Image blocks in a post', 'gatographql'),
                [
                    PowerExtensionModuleResolver::FIELD_ON_FIELD,
                    PowerExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                    PowerExtensionModuleResolver::FIELD_TO_INPUT,
                    PowerExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                    PowerExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    PowerExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],

            TutorialLessons::TRANSLATING_BLOCK_CONTENT_IN_A_POST_TO_A_DIFFERENT_LANGUAGE => [
                \__('Lesson 12: Translating block content in a post to a different language', 'gatographql'),
                [
                    PowerExtensionModuleResolver::FIELD_ON_FIELD,
                    PowerExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                    PowerExtensionModuleResolver::FIELD_TO_INPUT,
                    PowerExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                    PremiumExtensionModuleResolver::GOOGLE_TRANSLATE,
                    PowerExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    PowerExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],

            TutorialLessons::BULK_TRANSLATING_BLOCK_CONTENT_IN_MULTIPLE_POSTS_TO_A_DIFFERENT_LANGUAGE => [
                \__('Lesson 13: Bulk translating block content in multiple posts to a different language', 'gatographql'),
                [
                    PowerExtensionModuleResolver::CONDITIONAL_FIELD_MANIPULATION,
                    PowerExtensionModuleResolver::FIELD_ON_FIELD,
                    PowerExtensionModuleResolver::FIELD_TO_INPUT,
                    PowerExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                    PremiumExtensionModuleResolver::GOOGLE_TRANSLATE,
                    PowerExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    PowerExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],

            TutorialLessons::SENDING_EMAILS_WITH_PLEASURE => [
                \__('Lesson 14: Sending emails with pleasure', 'gatographql'),
                [
                    PowerExtensionModuleResolver::EMAIL_SENDER,
                    PowerExtensionModuleResolver::FIELD_TO_INPUT,
                    PowerExtensionModuleResolver::HELPER_FUNCTION_COLLECTION,
                    PowerExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    PowerExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],
            TutorialLessons::SENDING_A_NOTIFICATION_WHEN_THERE_IS_A_NEW_POST => [
                \__('Lesson 15: Sending a notification when there is a new post', 'gatographql'),
                [
                    PremiumExtensionModuleResolver::AUTOMATION,
                    PowerExtensionModuleResolver::EMAIL_SENDER,
                    PowerExtensionModuleResolver::FIELD_TO_INPUT,
                    PowerExtensionModuleResolver::HELPER_FUNCTION_COLLECTION,
                    PowerExtensionModuleResolver::INTERNAL_GRAPHQL_SERVER,
                    PowerExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    PowerExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],
            TutorialLessons::SENDING_A_DAILY_SUMMARY_OF_ACTIVITY => [
                \__('Lesson 16: Sending a daily summary of activity', 'gatographql'),
                [
                    PremiumExtensionModuleResolver::AUTOMATION,
                    PowerExtensionModuleResolver::EMAIL_SENDER,
                    PowerExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                    PowerExtensionModuleResolver::FIELD_TO_INPUT,
                    PowerExtensionModuleResolver::HELPER_FUNCTION_COLLECTION,
                    PowerExtensionModuleResolver::INTERNAL_GRAPHQL_SERVER,
                    PowerExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    PowerExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],
            TutorialLessons::AUTOMATICALLY_ADDING_A_MANDATORY_BLOCK => [
                \__('Lesson 17: Automatically adding a mandatory block', 'gatographql'),
                [
                    PowerExtensionModuleResolver::FIELD_TO_INPUT,
                    PowerExtensionModuleResolver::INTERNAL_GRAPHQL_SERVER,
                    PowerExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    PowerExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],
            TutorialLessons::INTERACTING_WITH_EXTERNAL_SERVICES_VIA_WEBHOOKS => [
                \__('Lesson 18: Interacting with external services via webhooks', 'gatographql'),
                [
                    PowerExtensionModuleResolver::EMAIL_SENDER,
                    PowerExtensionModuleResolver::FIELD_TO_INPUT,
                    PowerExtensionModuleResolver::HTTP_REQUEST_VIA_SCHEMA,
                    PowerExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    PowerExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],
            TutorialLessons::RETRIEVING_DATA_FROM_AN_EXTERNAL_API => [
                \__('Lesson 19: Retrieving data from an external API', 'gatographql'),
                [
                    PowerExtensionModuleResolver::FIELD_RESOLUTION_CACHING,
                    PowerExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                    PowerExtensionModuleResolver::FIELD_TO_INPUT,
                    PowerExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                    PowerExtensionModuleResolver::HTTP_CLIENT,
                    PowerExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    PowerExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],
            TutorialLessons::COMBINING_USER_DATA_FROM_DIFFERENT_SOURCES => [
                \__('Lesson 20: Combining user data from different sources', 'gatographql'),
                [
                    PowerExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    PowerExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],
            TutorialLessons::NOT_LEAKING_CREDENTIALS_WHEN_CONNECTING_TO_SERVICES => [
                \__('Lesson 21: Not leaking credentials when connecting to services', 'gatographql'),
                [
                    PowerExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                    PowerExtensionModuleResolver::FIELD_TO_INPUT,
                    PowerExtensionModuleResolver::HTTP_CLIENT,
                    PowerExtensionModuleResolver::PHP_CONSTANTS_AND_ENVIRONMENT_VARIABLES_VIA_SCHEMA,
                ]
            ],
            TutorialLessons::HANDLING_ERRORS_WHEN_CONNECTING_TO_SERVICES => [
                \__('Lesson 22: Handling errors when connecting to services', 'gatographql'),
                [
                    PowerExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                    PowerExtensionModuleResolver::FIELD_TO_INPUT,
                    PowerExtensionModuleResolver::HTTP_CLIENT,
                    PowerExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    PowerExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                    PowerExtensionModuleResolver::RESPONSE_ERROR_TRIGGER,
                ]
            ],
            TutorialLessons::CREATING_AN_API_GATEWAY => [
                \__('Lesson 23: Creating an API gateway', 'gatographql'),
                [
                    PowerExtensionModuleResolver::FIELD_ON_FIELD,
                    PowerExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                    PowerExtensionModuleResolver::FIELD_TO_INPUT,
                    PowerExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                    PowerExtensionModuleResolver::HTTP_CLIENT,
                    PowerExtensionModuleResolver::HTTP_REQUEST_VIA_SCHEMA,
                    PowerExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    PowerExtensionModuleResolver::PHP_CONSTANTS_AND_ENVIRONMENT_VARIABLES_VIA_SCHEMA,
                    PowerExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                    PowerExtensionModuleResolver::RESPONSE_ERROR_TRIGGER,
                ]
            ],

            TutorialLessons::TRANSLATING_CONTENT_FROM_URL => [
                \__('Lesson 24: Translating content from URL', 'gatographql'),
                [
                    PremiumExtensionModuleResolver::GOOGLE_TRANSLATE,
                    PowerExtensionModuleResolver::HTTP_CLIENT,
                ]
            ],

            TutorialLessons::TRANSFORMING_DATA_FROM_AN_EXTERNAL_API => [
                \__('Lesson 25: Transforming data from an external API', 'gatographql'),
                [
                    PowerExtensionModuleResolver::CONDITIONAL_FIELD_MANIPULATION,
                    PowerExtensionModuleResolver::FIELD_DEFAULT_VALUE,
                    PowerExtensionModuleResolver::FIELD_ON_FIELD,
                    PowerExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                    PowerExtensionModuleResolver::FIELD_TO_INPUT,
                    PowerExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                    PowerExtensionModuleResolver::HTTP_CLIENT,
                    PowerExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],
            TutorialLessons::FILTERING_DATA_FROM_AN_EXTERNAL_API => [
                \__('Lesson 26: Filtering data from an external API', 'gatographql'),
                [
                    PowerExtensionModuleResolver::CONDITIONAL_FIELD_MANIPULATION,
                    PowerExtensionModuleResolver::FIELD_ON_FIELD,
                    PowerExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                    PowerExtensionModuleResolver::HTTP_CLIENT,
                    PowerExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],
            TutorialLessons::PINGING_EXTERNAL_SERVICES => [
                \__('Lesson 27: Pinging external services', 'gatographql'),
                [
                    PowerExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                    PowerExtensionModuleResolver::FIELD_TO_INPUT,
                    PowerExtensionModuleResolver::HTTP_CLIENT,
                    PowerExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    PowerExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],
            TutorialLessons::UPDATING_LARGE_SETS_OF_DATA => [
                \__('Lesson 28: Updating large sets of data', 'gatographql'),
                [
                    PowerExtensionModuleResolver::FIELD_ON_FIELD,
                    PowerExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                    PowerExtensionModuleResolver::FIELD_TO_INPUT,
                    PowerExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                    PowerExtensionModuleResolver::HTTP_CLIENT,
                    PowerExtensionModuleResolver::HTTP_REQUEST_VIA_SCHEMA,
                    PowerExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    PowerExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],
            TutorialLessons::IMPORTING_A_POST_FROM_ANOTHER_WORDPRESS_SITE => [
                \__('Lesson 29: Importing a post from another WordPress site', 'gatographql'),
                [
                    PowerExtensionModuleResolver::CONDITIONAL_FIELD_MANIPULATION,
                    PowerExtensionModuleResolver::FIELD_ON_FIELD,
                    PowerExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                    PowerExtensionModuleResolver::FIELD_TO_INPUT,
                    PowerExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                    PowerExtensionModuleResolver::HTTP_CLIENT,
                    PowerExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    PowerExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                    PowerExtensionModuleResolver::RESPONSE_ERROR_TRIGGER,
                ]
            ],
            TutorialLessons::DISTRIBUTING_CONTENT_FROM_AN_UPSTREAM_TO_MULTIPLE_DOWNSTREAM_SITES => [
                \__('Lesson 30: Distributing content from an upstream to multiple downstream sites', 'gatographql'),
                [
                    PowerExtensionModuleResolver::FIELD_ON_FIELD,
                    PowerExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                    PowerExtensionModuleResolver::FIELD_TO_INPUT,
                    PowerExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                    PowerExtensionModuleResolver::HTTP_CLIENT,
                    PowerExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    PowerExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                    PowerExtensionModuleResolver::RESPONSE_ERROR_TRIGGER,
                ]
            ],
            TutorialLessons::AUTOMATICALLY_SENDING_NEWSLETTER_SUBSCRIBERS_FROM_INSTAWP_TO_MAILCHIMP => [
                \__('Lesson 31: Automatically sending newsletter subscribers from InstaWP to Mailchimp', 'gatographql'),
                [
                    PowerExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                    PowerExtensionModuleResolver::FIELD_TO_INPUT,
                    PowerExtensionModuleResolver::HTTP_CLIENT,
                    PowerExtensionModuleResolver::HTTP_REQUEST_VIA_SCHEMA,
                    PowerExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    PowerExtensionModuleResolver::PHP_CONSTANTS_AND_ENVIRONMENT_VARIABLES_VIA_SCHEMA,
                    PowerExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
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
