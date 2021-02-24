<?php

declare(strict_types=1);

namespace GraphQLAPI\ConvertCaseDirectives\SystemServices\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\Plugin;
use GraphQLAPI\GraphQLAPI\SystemServices\ModuleResolvers\ModuleResolverTrait;
use GraphQLAPI\GraphQLAPI\SystemServices\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\SystemServices\ModuleResolvers\AbstractSchemaTypeModuleResolver;
use PoPSchema\ConvertCaseDirectives\DirectiveResolvers\LowerCaseStringDirectiveResolver;
use PoPSchema\ConvertCaseDirectives\DirectiveResolvers\TitleCaseStringDirectiveResolver;
use PoPSchema\ConvertCaseDirectives\DirectiveResolvers\UpperCaseStringDirectiveResolver;

class SchemaModuleResolver extends AbstractSchemaTypeModuleResolver
{
    use ModuleResolverTrait;

    public const CONVERT_CASE_DIRECTIVES = Plugin::NAMESPACE . '\convert-case-directives';

    public static function getModulesToResolve(): array
    {
        return [
            self::CONVERT_CASE_DIRECTIVES,
        ];
    }

    public function getDependedModuleLists(string $module): array
    {
        switch ($module) {
            case self::CONVERT_CASE_DIRECTIVES:
                return [
                    [
                        EndpointFunctionalityModuleResolver::SINGLE_ENDPOINT,
                        EndpointFunctionalityModuleResolver::PERSISTED_QUERIES,
                        EndpointFunctionalityModuleResolver::CUSTOM_ENDPOINTS,
                    ],
                ];
        }
        return parent::getDependedModuleLists($module);
    }

    public function getName(string $module): string
    {
        $names = [
            self::CONVERT_CASE_DIRECTIVES => \__('Directive Set: Convert Lower/Title/Upper case', 'graphql-api'),
        ];
        return $names[$module] ?? $module;
    }

    public function getDescription(string $module): string
    {
        switch ($module) {
            case self::CONVERT_CASE_DIRECTIVES:
                return sprintf(
                    \__('Set of directives to manipulate strings: <code>@%s</code>, <code>@%s</code> and <code>@%s</code>', 'graphql-api'),
                    UpperCaseStringDirectiveResolver::getDirectiveName(),
                    LowerCaseStringDirectiveResolver::getDirectiveName(),
                    TitleCaseStringDirectiveResolver::getDirectiveName()
                );
        }
        return parent::getDescription($module);
    }
}
