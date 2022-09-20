<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

/**
 * Interface that indicates what directives will export values
 * to dynamic variables.
 *
 * By default, this is expected to be `@export(as: "variableName")`,
 * but the GraphQL spec does not define it. Then, through this
 * interface, we can provide different variations, or even
 * different directives, to provide this functionality.
 *
 * @see https://github.com/graphql/graphql-spec/issues/583
 */
interface DynamicVariableDefinerFieldDirectiveResolverInterface extends DirectiveResolverInterface
{
    /**
     * Name for the directive arg to indicate the name of the
     * dynamic variable.
     *
     * Eg: @export(as: "variableName")
     */
    public function getExportUnderVariableNameArgumentName(): string;

    /**
     * If `true`, the dynamic variable's scope is the object
     * being currently resolved.
     *
     * If `false`, the exported value is available for all
     * subsequent AST elements in the document.
     */
    public function mustResolveDynamicVariableOnObject(): bool;
}
