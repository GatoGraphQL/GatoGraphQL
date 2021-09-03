<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

interface SchemaDirectiveResolverInterface
{
    /**
     * Description of the directive, to be output as documentation in the schema
     */
    public function getSchemaDirectiveDescription(RelationalTypeResolverInterface $relationalTypeResolver): ?string;
    /**
     * Indicates if the directive argument names can be omitted from the query, deducing them from the order in which they were defined in the schema
     */
    public function enableOrderedSchemaDirectiveArgs(RelationalTypeResolverInterface $relationalTypeResolver): bool;
    /**
     * Schema Directive Arguments
     */
    public function getSchemaDirectiveArgs(RelationalTypeResolverInterface $relationalTypeResolver): array;
    /**
     * Expressions set by the directive
     */
    public function getSchemaDirectiveExpressions(RelationalTypeResolverInterface $relationalTypeResolver): array;
    /**
     * Raise warnings concerning the directive
     */
    public function getSchemaDirectiveWarningDescription(RelationalTypeResolverInterface $relationalTypeResolver): ?string;
    /**
     * Indicate if the directive has been deprecated, why, when, and/or how it must be replaced
     */
    public function getSchemaDirectiveDeprecationDescription(RelationalTypeResolverInterface $relationalTypeResolver): ?string;
    /**
     * Indicate if the directive is global (i.e. it can be applied to all fields, for all typeResolvers)
     */
    public function isGlobal(RelationalTypeResolverInterface $relationalTypeResolver): bool;
}
