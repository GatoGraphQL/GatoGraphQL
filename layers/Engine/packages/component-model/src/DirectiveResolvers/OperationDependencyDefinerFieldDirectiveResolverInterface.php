<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

/**
 * Interface that indicates what Operation Directives will add
 * dependencies to load previous Operations from the same
 * GraphQL Document, to be processed in the same request.
 *
 * By default, this will be `@depends(on: ["op1", "op2", etc])`,
 * but the GraphQL spec does not define it. Then, through this
 * interface, we can provide different variations, or even
 * different directives, to provide this functionality.
 */
interface OperationDependencyDefinerFieldDirectiveResolverInterface extends FieldDirectiveResolverInterface
{
    /**
     * Name of the Directive Argument that provides
     * the names of the Operations in the GraphQL Document
     * that must be loaded and processed before.
     *
     * Eg: "on" for `@depends(on: ["GetPosts", "ProcessPostData"])`
     */
    public function getProvideDependedUponOperationNamesArgumentName(): string;
}
