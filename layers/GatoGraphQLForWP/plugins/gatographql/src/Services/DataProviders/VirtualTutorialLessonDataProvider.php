<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\DataProviders;

use GatoGraphQL\GatoGraphQL\Constants\VirtualTutorialLessons;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions\ExtensionModuleResolver;

class VirtualTutorialLessonDataProvider
{
    /**
     * @return array<string,array{0:string,1?:string[],2?:string[]}> Key: tutorial lesson file slug, Value: [0] => title, [1] => array of extension modules
     */
    public function getTutorialLessonSlugDataItems(): array
    {
        return [
            VirtualTutorialLessons::IMPORTING_A_POST_FROM_WORDPRESS_RSS_FEED => [
                \__('Importing a post from WordPress RSS feed', 'gatographql'),
                [
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::HELPER_FUNCTION_COLLECTION,
                    ExtensionModuleResolver::HTTP_CLIENT,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],
            VirtualTutorialLessons::IMPORTING_POSTS_FROM_A_CSV => [
                \__('Importing posts from a CSV', 'gatographql'),
                [
                    ExtensionModuleResolver::FIELD_ON_FIELD,
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                    ExtensionModuleResolver::HELPER_FUNCTION_COLLECTION,
                    ExtensionModuleResolver::HTTP_CLIENT,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],
            VirtualTutorialLessons::TRANSLATING_CLASSIC_EDITOR_POST_TO_A_DIFFERENT_LANGUAGE => [
                \__('Translating "Classic editor" post to a different language', 'gatographql'),
                [
                    ExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                    ExtensionModuleResolver::GOOGLE_TRANSLATE,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],
            VirtualTutorialLessons::BULK_TRANSLATING_CLASSIC_EDITOR_POSTS_TO_A_DIFFERENT_LANGUAGE => [
                \__('Bulk translating "Classic editor" posts to a different language', 'gatographql'),
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
            VirtualTutorialLessons::FETCH_POST_LINKS => [
                \__('Retrieving post links', 'gatographql'),
                [
                    ExtensionModuleResolver::CONDITIONAL_FIELD_MANIPULATION,
                    ExtensionModuleResolver::FIELD_ON_FIELD,
                    ExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],
            VirtualTutorialLessons::SEND_EMAIL_TO_ADMIN_ABOUT_POST => [
                \__('Send email to admin about post', 'gatographql'),
                [
                    ExtensionModuleResolver::EMAIL_SENDER,
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::HELPER_FUNCTION_COLLECTION,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],
            VirtualTutorialLessons::SEND_EMAIL_TO_USERS_ABOUT_POST => [
                \__('Send email to users about post', 'gatographql'),
                [
                    ExtensionModuleResolver::EMAIL_SENDER,
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::HELPER_FUNCTION_COLLECTION,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],
            VirtualTutorialLessons::ADD_COMMENTS_BLOCK_TO_POST => [
                \__('Add comments block to post', 'gatographql'),
                [
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],
            VirtualTutorialLessons::GENERATE_A_POST_FEATURED_IMAGE_USING_AI_AND_OPTIMIZE_IT => [
                \__('Generate a post\'s featured image using AI and optimize it', 'gatographql'),
                [
                    ExtensionModuleResolver::FIELD_DEFAULT_VALUE,
                    ExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                    ExtensionModuleResolver::HTTP_CLIENT,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],
            VirtualTutorialLessons::TRANSLATING_POEDIT_FILE_CONTENT => [
                \__('Translating empty strings in a Poedit file, and storing it as a new Poedit file on Filestack', 'gatographql'),
                [
                    ExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                    ExtensionModuleResolver::GOOGLE_TRANSLATE,
                    ExtensionModuleResolver::HTTP_CLIENT,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],
            VirtualTutorialLessons::TRANSLATING_POSTS_FOR_POLYLANG_AND_GUTENBERG => [
                \__('Translating block content in a post to a different language, with integration for Polylang', 'gatographql'),
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
            VirtualTutorialLessons::TRANSLATING_POSTS_FOR_POLYLANG_AND_CLASSIC_EDITOR => [
                \__('Translating "Classic editor" post to a different language, with integration for Polylang', 'gatographql'),
                [
                    ExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                    ExtensionModuleResolver::GOOGLE_TRANSLATE,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],
            VirtualTutorialLessons::SYNCHRONIZING_FEATUREDIMAGE_FOR_POLYLANG => [
                \__('Synchronizing the featured image to the different language one defined via Polylang', 'gatographql'),
                [
                    ExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],
            VirtualTutorialLessons::SYNCHRONIZING_TAGS_AND_CATEGORIES_FOR_POLYLANG => [
                \__('Synchronizing the tags and categories to the different language one defined via Polylang', 'gatographql'),
                [
                    ExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],
            VirtualTutorialLessons::TRANSLATING_AND_CREATING_ALL_PAGES_FOR_MULTILINGUAL_WORDPRESS_SITE_GUTENBERG => [
                \__('Grabbing all block-based pages from the main site in the Multisite network, and translating them and creating them on a given translation site', 'gatographql'),
                [
                    ExtensionModuleResolver::FIELD_ON_FIELD,
                    ExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                    ExtensionModuleResolver::GOOGLE_TRANSLATE,
                    ExtensionModuleResolver::HTTP_CLIENT,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],
            VirtualTutorialLessons::TRANSLATING_AND_CREATING_ALL_PAGES_FOR_MULTILINGUAL_WORDPRESS_SITE_CLASSIC_EDITOR => [
                \__('Grabbing all classic editor pages from the main site in the Multisite network, and translating them and creating them on a given translation site', 'gatographql'),
                [
                    ExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                    ExtensionModuleResolver::GOOGLE_TRANSLATE,
                    ExtensionModuleResolver::HTTP_CLIENT,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],
            VirtualTutorialLessons::TRANSLATING_POSTS_FOR_MULTILINGUALPRESS_AND_GUTENBERG => [
                \__('Translating block content in a post to a different language, with integration for MultilingualPress', 'gatographql'),
                [
                    ExtensionModuleResolver::FIELD_ON_FIELD,
                    ExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                    ExtensionModuleResolver::GOOGLE_TRANSLATE,
                    ExtensionModuleResolver::HTTP_CLIENT,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],
            VirtualTutorialLessons::TRANSLATING_POSTS_FOR_MULTILINGUALPRESS_AND_CLASSIC_EDITOR => [
                \__('Translating "Classic editor" post to a different language, with integration for MultilingualPress', 'gatographql'),
                [
                    ExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                    ExtensionModuleResolver::GOOGLE_TRANSLATE,
                    ExtensionModuleResolver::HTTP_CLIENT,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],
            VirtualTutorialLessons::CREATING_MISSING_TRANSLATION_POSTS_FOR_POLYLANG => [
                \__('Creating missing translation posts for Polylang', 'gatographql'),
                [
                    ExtensionModuleResolver::FIELD_ON_FIELD,
                    ExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],
            VirtualTutorialLessons::TRANSLATING_CATEGORIES_FOR_POLYLANG => [
                \__('Translating a category to a different language, with integration for Polylang', 'gatographql'),
                [
                    ExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                    ExtensionModuleResolver::GOOGLE_TRANSLATE,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],
            VirtualTutorialLessons::CREATING_MISSING_TRANSLATION_CATEGORIES_FOR_POLYLANG => [
                \__('Creating missing translation categories for Polylang', 'gatographql'),
                [
                    ExtensionModuleResolver::FIELD_ON_FIELD,
                    ExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],
            VirtualTutorialLessons::TRANSLATING_TAGS_FOR_POLYLANG => [
                \__('Translating a tag to a different language, with integration for Polylang', 'gatographql'),
                [
                    ExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                    ExtensionModuleResolver::GOOGLE_TRANSLATE,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],
            VirtualTutorialLessons::CREATING_MISSING_TRANSLATION_TAGS_FOR_POLYLANG => [
                \__('Creating missing translation tags for Polylang', 'gatographql'),
                [
                    ExtensionModuleResolver::FIELD_ON_FIELD,
                    ExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ]
            ],
        ];
    }
}
