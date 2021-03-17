<?php

declare(strict_types=1);

namespace GraphQLAPI\ConvertCaseDirectives\ModuleResolvers;

use GraphQLAPI\ConvertCaseDirectives\GraphQLAPIExtension;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\AbstractSchemaTypeModuleResolver;
use PoPSchema\ConvertCaseDirectives\DirectiveResolvers\LowerCaseStringDirectiveResolver;
use PoPSchema\ConvertCaseDirectives\DirectiveResolvers\TitleCaseStringDirectiveResolver;
use PoPSchema\ConvertCaseDirectives\DirectiveResolvers\UpperCaseStringDirectiveResolver;

class SchemaModuleResolver extends AbstractSchemaTypeModuleResolver
{
    use ModuleResolverTrait;

    public const CONVERT_CASE_DIRECTIVES = GraphQLAPIExtension::NAMESPACE . '\convert-case-directives';

    /**
     * Make all properties nullable, becase the ModuleRegistry is registered
     * in the SystemContainer, where there are no typeResolvers so it will be null,
     * and in the ApplicationContainer, from where the "Modules" page is resolved
     * and which does have all the typeResolvers.
     * Function `getDescription` will only be accessed from the Application Container,
     * so the properties will not be null in that situation.
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

    public function getModulesToResolve(): array
    {
        return [
            self::CONVERT_CASE_DIRECTIVES,
        ];
    }

    /**
     * The priority to display the modules from this resolver in the Modules page.
     * The higher the number, the earlier it shows
     */
    public function getPriority(): int
    {
        return 90;
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
