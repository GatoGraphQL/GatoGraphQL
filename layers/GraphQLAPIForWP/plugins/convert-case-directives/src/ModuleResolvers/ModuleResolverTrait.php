<?php

declare(strict_types=1);

namespace GraphQLAPI\ConvertCaseDirectives\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\HasMarkdownDocumentationModuleResolverTrait;

trait ModuleResolverTrait
{
    use HasMarkdownDocumentationModuleResolverTrait;

    /**
     * Get the dir where to look for the documentation.
     */
    protected function getBaseDir(): string
    {
        return constant('GRAPHQL_API_CONVERT_CASE_DIRECTIVES_DIR');
    }

    /**
     * Get the URL where to look for the documentation.
     */
    protected function getBaseURL(): string
    {
        return constant('GRAPHQL_API_CONVERT_CASE_DIRECTIVES_DIR');
    }
}
