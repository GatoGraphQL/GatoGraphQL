<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;

interface SchemaDirectiveResolverInterface
{
    /**
     * Description of the directive, to be output as documentation in the schema
     */
    public function getSchemaDirectiveDescription(TypeResolverInterface $typeResolver): ?string;
    /**
     * Indicates if the directive argument names can be omitted from the query, deducing them from the order in which they were defined in the schema
     *
     * @param string $directive
     */
    public function enableOrderedSchemaDirectiveArgs(TypeResolverInterface $typeResolver): bool;
    /**
     * Schema Directive Arguments
     */
    public function getSchemaDirectiveArgs(TypeResolverInterface $typeResolver): array;
    /**
     * Filtered Schema Directive Arguments
     */
    public function getFilteredSchemaDirectiveArgs(TypeResolverInterface $typeResolver): array;
    /**
     * Expressions set by the directive
     */
    public function getSchemaDirectiveExpressions(TypeResolverInterface $typeResolver): array;
    /**
     * Raise warnings concerning the directive
     */
    public function getSchemaDirectiveWarningDescription(TypeResolverInterface $typeResolver): ?string;
    /**
     * Indicate if the directive has been deprecated, why, when, and/or how it must be replaced
     */
    public function getSchemaDirectiveDeprecationDescription(TypeResolverInterface $typeResolver): ?string;
    /**
     * Indicate if the directive is global (i.e. it can be applied to all fields, for all typeResolvers)
     */
    public function isGlobal(TypeResolverInterface $typeResolver): bool;
}
