<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Container\CompilerPasses;

use GraphQLAPI\GraphQLAPI\Constants\EndpointConfigurationGroups;
use GraphQLByPoP\GraphQLServer\Environment as GraphQLServerEnvironment;
use GraphQLByPoP\GraphQLServer\Module as GraphQLServerModule;
use PoP\ComponentModel\Environment as ComponentModelEnvironment;
use PoP\ComponentModel\Module as ComponentModelModule;
use PoP\Engine\Environment as EngineEnvironment;
use PoP\Engine\Module as EngineModule;
use PoP\Root\Module\ModuleInterface;
use PoPCMSSchema\CommentMeta\Environment as CommentMetaEnvironment;
use PoPCMSSchema\CommentMeta\Module as CommentMetaModule;
use PoPCMSSchema\CustomPostMeta\Environment as CustomPostMetaEnvironment;
use PoPCMSSchema\CustomPostMeta\Module as CustomPostMetaModule;
use PoPCMSSchema\Settings\Environment as SettingsEnvironment;
use PoPCMSSchema\Settings\Module as SettingsModule;
use PoPCMSSchema\TaxonomyMeta\Environment as TaxonomyMetaEnvironment;
use PoPCMSSchema\TaxonomyMeta\Module as TaxonomyMetaModule;
use PoPCMSSchema\UserMeta\Environment as UserMetaEnvironment;
use PoPCMSSchema\UserMeta\Module as UserMetaModule;
use PoPSchema\SchemaCommons\Constants\Behaviors;

class PluginInternalWPEditorAdminEndpointGroupModuleConfigurationCompilerPass extends AbstractAdminEndpointGroupModuleConfigurationCompilerPass
{
    /**
     * @return array<string,array<string,mixed>>
     * @phpstan-return array<class-string<ModuleInterface>,array<string,mixed>>
     */
    protected function getModuleClassConfiguration(): array
    {
        return [
            ComponentModelModule::class => [
                // Enable the “sensitive” data
                ComponentModelEnvironment::EXPOSE_SENSITIVE_DATA_IN_SCHEMA => true,
                // Enable the "self" fields
                ComponentModelEnvironment::ENABLE_SELF_FIELD => true
            ],
            GraphQLServerModule::class => [
                // Enable Nested mutations
                GraphQLServerEnvironment::ENABLE_NESTED_MUTATIONS => true,
            ],
            EngineModule::class => [
                // Do not disable redundant mutation fields in the root type
                EngineEnvironment::DISABLE_REDUNDANT_ROOT_TYPE_MUTATION_FIELDS => false,
            ],
            // Allow access to all entries for Root.option
            SettingsModule::class => [
                SettingsEnvironment::SETTINGS_ENTRIES => [],
                SettingsEnvironment::SETTINGS_BEHAVIOR => Behaviors::DENY,
            ],
            // Allow access to all meta values
            CustomPostMetaModule::class => [
                CustomPostMetaEnvironment::CUSTOMPOST_META_ENTRIES => [],
                CustomPostMetaEnvironment::CUSTOMPOST_META_BEHAVIOR => Behaviors::DENY,
            ],
            UserMetaModule::class => [
                UserMetaEnvironment::USER_META_ENTRIES => [],
                UserMetaEnvironment::USER_META_BEHAVIOR => Behaviors::DENY,
            ],
            CommentMetaModule::class => [
                CommentMetaEnvironment::COMMENT_META_ENTRIES => [],
                CommentMetaEnvironment::COMMENT_META_BEHAVIOR => Behaviors::DENY,
            ],
            TaxonomyMetaModule::class => [
                TaxonomyMetaEnvironment::TAXONOMY_META_ENTRIES => [],
                TaxonomyMetaEnvironment::TAXONOMY_META_BEHAVIOR => Behaviors::DENY,
            ],
            // Do not use the Payloadable types for mutations
            \PoPCMSSchema\CommentMutations\Module::class => [
                \PoPCMSSchema\CommentMutations\Environment::USE_PAYLOADABLE_COMMENT_MUTATIONS => false,
            ],
            \PoPCMSSchema\CustomPostCategoryMutations\Module::class => [
                \PoPCMSSchema\CustomPostCategoryMutations\Environment::USE_PAYLOADABLE_CUSTOMPOSTCATEGORY_MUTATIONS => false,
            ],
            \PoPCMSSchema\CustomPostMutations\Module::class => [
                \PoPCMSSchema\CustomPostMutations\Environment::USE_PAYLOADABLE_CUSTOMPOST_MUTATIONS => false,
            ],
            \PoPCMSSchema\CustomPostTagMutations\Module::class => [
                \PoPCMSSchema\CustomPostTagMutations\Environment::USE_PAYLOADABLE_CUSTOMPOSTTAG_MUTATIONS => false,
            ],
            \PoPCMSSchema\CustomPostMediaMutations\Module::class => [
                \PoPCMSSchema\CustomPostMediaMutations\Environment::USE_PAYLOADABLE_CUSTOMPOSTMEDIA_MUTATIONS => false,
            ],
            \PoPCMSSchema\UserStateMutations\Module::class => [
                \PoPCMSSchema\UserStateMutations\Environment::USE_PAYLOADABLE_USERSTATE_MUTATIONS => false,
            ],
        ];
    }

    protected function getEndpointGroup(): string
    {
        return EndpointConfigurationGroups::PLUGIN_INTERNAL_WP_EDITOR;
    }
}
