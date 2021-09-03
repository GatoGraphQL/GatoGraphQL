<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

interface SchemaDirectiveResolverInterface
{
    /**
     * Description of the directive, to be output as documentation in the schema
     */
    public function getSchemaDirectiveDescription(RelationalTypeResolverInterface $typeResolver): ?string;
    /**
     * Indicates if the directive argument names can be omitted from the query, deducing them from the order in which they were defined in the schema
     */
    public function enableOrderedSchemaDirectiveArgs(RelationalTypeResolverInterface $typeResolver): bool;
    /**
     * Schema Directive Arguments
     */
    public function getSchemaDirectiveArgs(RelationalTypeResolverInterface $typeResolver): array;
    /**
     * Expressions set by the directive
     */
    public function getSchemaDirectiveExpressions(RelationalTypeResolverInterface $typeResolver): array;
    /**
     * Raise warnings concerning the directive
     */
    public function getSchemaDirectiveWarningDescription(RelationalTypeResolverInterface $typeResolver): ?string;
    /**
     * Indicate if the directive has been deprecated, why, when, and/or how it must be replaced
     */
    public function getSchemaDirectiveDeprecationDescription(RelationalTypeResolverInterface $typeResolver): ?string;
    /**
     * Indicate if the directive is global (i.e. it can be applied to all fields, for all typeResolvers)
     */
    public function isGlobal(RelationalTypeResolverInterface $typeResolver): bool;
}
