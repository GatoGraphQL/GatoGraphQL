<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnContext\PROPluginPseudoModules\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\ContentProcessors\MarkdownContentParserInterface;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\AbstractFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\PluginManagementFunctionalityModuleResolverTrait;
use GraphQLAPI\GraphQLAPI\ModuleSettings\Properties;
use GraphQLAPI\GraphQLAPI\Registries\UserAuthorizationSchemeRegistryInterface;
use GraphQLAPI\GraphQLAPI\Plugin;

class PluginManagementFunctionalityModuleResolver extends AbstractFunctionalityModuleResolver
{
    use ModuleResolverTrait;
    use PluginManagementFunctionalityModuleResolverTrait;

    public final const SCHEMA_EDITING_ACCESS = 'placeholder:' . Plugin::NAMESPACE . '\schema-editing-access';

    // /**
    //  * Setting options
    //  */
    // public final const OPTION_EDITING_ACCESS_SCHEME = 'editing-access-scheme';

    private ?UserAuthorizationSchemeRegistryInterface $userAuthorizationSchemeRegistry = null;
    private ?MarkdownContentParserInterface $markdownContentParser = null;

    final public function setUserAuthorizationSchemeRegistry(UserAuthorizationSchemeRegistryInterface $userAuthorizationSchemeRegistry): void
    {
        $this->userAuthorizationSchemeRegistry = $userAuthorizationSchemeRegistry;
    }
    final protected function getUserAuthorizationSchemeRegistry(): UserAuthorizationSchemeRegistryInterface
    {
        /** @var UserAuthorizationSchemeRegistryInterface */
        return $this->userAuthorizationSchemeRegistry ??= $this->instanceManager->getInstance(UserAuthorizationSchemeRegistryInterface::class);
    }
    final public function setMarkdownContentParser(MarkdownContentParserInterface $markdownContentParser): void
    {
        $this->markdownContentParser = $markdownContentParser;
    }
    final protected function getMarkdownContentParser(): MarkdownContentParserInterface
    {
        /** @var MarkdownContentParserInterface */
        return $this->markdownContentParser ??= $this->instanceManager->getInstance(MarkdownContentParserInterface::class);
    }

    /**
     * @return string[]
     */
    public function getModulesToResolve(): array
    {
        return [
            self::SCHEMA_EDITING_ACCESS,
        ];
    }

    public function getName(string $module): string
    {
        return match ($module) {
            self::SCHEMA_EDITING_ACCESS => \__('Schema Editing Access [PRO]', 'graphql-api'),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        switch ($module) {
            case self::SCHEMA_EDITING_ACCESS:
                return \__('Grant access to users other than admins to edit the GraphQL schema', 'graphql-api');
        }
        return parent::getDescription($module);
    }

    // /**
    //  * Default value for an option set by the module
    //  */
    // public function getSettingsDefaultValue(string $module, string $option): mixed
    // {
    //     $defaultUserAuthorizationScheme = $this->getUserAuthorizationSchemeRegistry()->getDefaultUserAuthorizationScheme();
    //     $defaultValues = [
    //         self::SCHEMA_EDITING_ACCESS => [
    //             self::OPTION_EDITING_ACCESS_SCHEME => $defaultUserAuthorizationScheme->getName(),
    //         ],
    //     ];
    //     return $defaultValues[$module][$option] ?? null;
    // }

    // /**
    //  * Array with the inputs to show as settings for the module
    //  *
    //  * @return array<array<string,mixed>> List of settings for the module, each entry is an array with property => value
    //  */
    // public function getSettings(string $module): array
    // {
    //     $moduleSettings = parent::getSettings($module);
    //     // Do the if one by one, so that the SELECT do not get evaluated unless needed
    //     if ($module === self::SCHEMA_EDITING_ACCESS) {
    //         $possibleValues = [];
    //         foreach ($this->getUserAuthorizationSchemeRegistry()->getUserAuthorizationSchemes() as $userAuthorizationScheme) {
    //             $possibleValues[$userAuthorizationScheme->getName()] = $userAuthorizationScheme->getDescription();
    //         }
    //         /**
    //          * Write Access Scheme
    //          * If `"admin"`, only the admin can compose a GraphQL query and endpoint
    //          * If `"post"`, the workflow from creating posts is employed (i.e. Author role can create
    //          * but not publish the query, Editor role can publish it, etc)
    //          */
    //         $option = self::OPTION_EDITING_ACCESS_SCHEME;
    //         $moduleSettings[] = [
    //             Properties::INPUT => $option,
    //             Properties::NAME => $this->getSettingOptionName(
    //                 $module,
    //                 $option
    //             ),
    //             Properties::TITLE => \__('Editing Access Scheme', 'graphql-api'),
    //             Properties::DESCRIPTION => \__('Scheme to decide which users can edit the schema (Persisted Queries, Custom Endpoints and related post types) and with what permissions', 'graphql-api'),
    //             Properties::TYPE => Properties::TYPE_STRING,
    //             Properties::POSSIBLE_VALUES => $possibleValues,
    //         ];
    //     }
    //     return $moduleSettings;
    // }
}
