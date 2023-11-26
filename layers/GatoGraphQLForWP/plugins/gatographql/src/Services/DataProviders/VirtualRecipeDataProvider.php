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
            VirtualRecipes::RETRIEVE_POST_LINKS => [
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
