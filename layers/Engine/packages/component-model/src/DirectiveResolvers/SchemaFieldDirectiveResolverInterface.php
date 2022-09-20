<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

interface SchemaFieldDirectiveResolverInterface
{
    /**
     * Description of the directive, to be output as documentation in the schema
     */
    public function getDirectiveDescription(RelationalTypeResolverInterface $relationalTypeResolver): ?string;
    /**
     * Define Schema Directive Arguments
     *
     * @return array<string,InputTypeResolverInterface>
     */
    public function getDirectiveArgNameTypeResolvers(RelationalTypeResolverInterface $relationalTypeResolver): array;
    public function getDirectiveArgDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): ?string;
    public function getDirectiveArgDefaultValue(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): mixed;
    public function getDirectiveArgTypeModifiers(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): int;
    /**
     * Invoke Schema Directive Arguments
     *
     * @return array<string,InputTypeResolverInterface>
     */
    public function getConsolidatedDirectiveArgNameTypeResolvers(RelationalTypeResolverInterface $relationalTypeResolver): array;
    public function getConsolidatedDirectiveArgDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): ?string;
    public function getConsolidatedDirectiveArgDefaultValue(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): mixed;
    public function getConsolidatedDirectiveArgTypeModifiers(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): ?int;
    /**
     * Indicate if the directive has been deprecated, why, when, and/or how it must be replaced
     */
    public function getDirectiveDeprecationMessage(RelationalTypeResolverInterface $relationalTypeResolver): ?string;
    /**
     * Indicate if the directive is global (i.e. it can be applied to all fields, for all typeResolvers)
     */
    public function isGlobal(RelationalTypeResolverInterface $relationalTypeResolver): bool;
}
