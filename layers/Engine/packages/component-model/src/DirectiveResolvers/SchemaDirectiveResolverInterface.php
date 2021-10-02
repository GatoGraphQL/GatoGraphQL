<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

interface SchemaDirectiveResolverInterface
{
    /**
     * Description of the directive, to be output as documentation in the schema
     */
    public function getDirectiveDescription(RelationalTypeResolverInterface $relationalTypeResolver): ?string;
    /**
     * Indicates if the directive argument names can be omitted from the query, deducing them from the order in which they were defined in the schema
     */
    public function enableOrderedSchemaDirectiveArgs(RelationalTypeResolverInterface $relationalTypeResolver): bool;
    /**
     * Define Schema Directive Arguments
     *
     * @return array<string, InputTypeResolverInterface>
     */
    public function getDirectiveArgNameResolvers(RelationalTypeResolverInterface $relationalTypeResolver): array;
    public function getDirectiveArgDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): ?string;
    public function getDirectiveArgDefaultValue(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): mixed;
    public function getDirectiveArgTypeModifiers(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): int;
    /**
     * Invoke Schema Directive Arguments
     *
     * @return array<string, InputTypeResolverInterface>
     */
    public function getSchemaDirectiveArgNameResolvers(RelationalTypeResolverInterface $relationalTypeResolver): array;
    public function getSchemaDirectiveArgDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): ?string;
    public function getSchemaDirectiveArgDefaultValue(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): mixed;
    public function getSchemaDirectiveArgTypeModifiers(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): ?int;
    /**
     * Expressions set by the directive
     */
    public function getDirectiveExpressions(RelationalTypeResolverInterface $relationalTypeResolver): array;
    /**
     * Raise warnings concerning the directive
     */
    public function getDirectiveWarningDescription(RelationalTypeResolverInterface $relationalTypeResolver): ?string;
    /**
     * Indicate if the directive has been deprecated, why, when, and/or how it must be replaced
     */
    public function getDirectiveDeprecationDescription(RelationalTypeResolverInterface $relationalTypeResolver): ?string;
    /**
     * Indicate if the directive is global (i.e. it can be applied to all fields, for all typeResolvers)
     */
    public function isGlobal(RelationalTypeResolverInterface $relationalTypeResolver): bool;
}
