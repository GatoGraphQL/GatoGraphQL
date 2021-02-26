<?php

declare(strict_types=1);

namespace GraphQLAPI\ConvertCaseDirectives\HybridServices\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\Plugin;
use GraphQLAPI\GraphQLAPI\HybridServices\ModuleResolvers\ModuleResolverTrait;
use GraphQLAPI\GraphQLAPI\HybridServices\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\HybridServices\ModuleResolvers\AbstractSchemaTypeModuleResolver;
use PoPSchema\ConvertCaseDirectives\DirectiveResolvers\LowerCaseStringDirectiveResolver;
use PoPSchema\ConvertCaseDirectives\DirectiveResolvers\TitleCaseStringDirectiveResolver;
use PoPSchema\ConvertCaseDirectives\DirectiveResolvers\UpperCaseStringDirectiveResolver;

class SchemaModuleResolver extends AbstractSchemaTypeModuleResolver
{
    use ModuleResolverTrait;

    public const CONVERT_CASE_DIRECTIVES = Plugin::NAMESPACE . '\convert-case-directives';

    /**
    * Make all properties nullable, becase the ModuleRegistry is registered
    * in the SystemContainer, where there are no typeResolvers so it will be null,
    * and in the ApplicationContainer, from where the "Modules" page is resolved
    * and which does have all the typeResolvers
    */
    protected ?UpperCaseStringDirectiveResolver $upperCaseStringDirectiveResolver;
    protected ?LowerCaseStringDirectiveResolver $lowerCaseStringDirectiveResolver;
    protected ?TitleCaseStringDirectiveResolver $titleCaseStringDirectiveResolver;

    public function __construct(
        ?UpperCaseStringDirectiveResolver $upperCaseStringDirectiveResolver,
        ?LowerCaseStringDirectiveResolver $lowerCaseStringDirectiveResolver,
        ?TitleCaseStringDirectiveResolver $titleCaseStringDirectiveResolver
    ) {
        $this->upperCaseStringDirectiveResolver = $upperCaseStringDirectiveResolver;
        $this->lowerCaseStringDirectiveResolver = $lowerCaseStringDirectiveResolver;
        $this->titleCaseStringDirectiveResolver = $titleCaseStringDirectiveResolver;
    }

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
                    $this->upperCaseStringDirectiveResolver->getDirectiveName(),
                    $this->lowerCaseStringDirectiveResolver->getDirectiveName(),
                    $this->titleCaseStringDirectiveResolver->getDirectiveName()
                );
        }
        return parent::getDescription($module);
    }
}
