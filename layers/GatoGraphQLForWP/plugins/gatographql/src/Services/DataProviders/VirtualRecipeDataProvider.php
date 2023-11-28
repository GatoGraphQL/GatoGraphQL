<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\DataProviders;

use GatoGraphQL\GatoGraphQL\Constants\VirtualRecipes;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions\BundleExtensionModuleResolver;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions\ExtensionModuleResolver;

class VirtualRecipeDataProvider
{
    /**
     * @return array<string,array{0:string,1?:string[],2?:string[]}> Key: recipe file slug, Value: [0] => title, [1] => array of extensions, [2] => array of bundles
     */
    public function getRecipeSlugDataItems(): array
    {
        return [
            VirtualRecipes::IMPORTING_A_POST_FROM_WORDPRESS_RSS_FEED => [
                \__('Importing a post from WordPress RSS feed', 'gatographql'),
                [
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::HELPER_FUNCTION_COLLECTION,
                    ExtensionModuleResolver::HTTP_CLIENT,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ],
                [
                    BundleExtensionModuleResolver::APPLICATION_GLUE_AND_AUTOMATOR,
                ]
            ],
            VirtualRecipes::IMPORTING_POSTS_FROM_A_CSV => [
                \__('Importing posts from a CSV', 'gatographql'),
                [
                    ExtensionModuleResolver::FIELD_ON_FIELD,
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                    ExtensionModuleResolver::HELPER_FUNCTION_COLLECTION,
                    ExtensionModuleResolver::HTTP_CLIENT,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ],
                [
                    BundleExtensionModuleResolver::APPLICATION_GLUE_AND_AUTOMATOR,
                ]
            ],
            VirtualRecipes::TRANSLATING_CLASSIC_EDITOR_POST_TO_A_DIFFERENT_LANGUAGE => [
                \__('Translating "Classic editor" post to a different language', 'gatographql'),
                [
                    ExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                    ExtensionModuleResolver::GOOGLE_TRANSLATE,
                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ],
                [
                    BundleExtensionModuleResolver::CONTENT_TRANSLATION,
                ]
            ],
            VirtualRecipes::BULK_TRANSLATING_CLASSIC_EDITOR_POSTS_TO_A_DIFFERENT_LANGUAGE => [
                \__('Bulk translating "Classic editor" posts to a different language', 'gatographql'),
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
            VirtualRecipes::FETCH_POST_LINKS => [
                \__('Retrieving post links', 'gatographql'),
                [
                    ExtensionModuleResolver::CONDITIONAL_FIELD_MANIPULATION,
                    ExtensionModuleResolver::FIELD_ON_FIELD,
                    ExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                    ExtensionModuleResolver::FIELD_TO_INPUT,
                    ExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                    ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ],
                [
                    BundleExtensionModuleResolver::APPLICATION_GLUE_AND_AUTOMATOR,
                ]
            ],
        ];
    }
}
