<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;

interface SchemaDirectiveResolverInterface
{
    /**
     * Description of the directive, to be output as documentation in the schema
     *
     * @param TypeResolverInterface $typeResolver
     * @return string|null
     */
    public function getSchemaDirectiveDescription(TypeResolverInterface $typeResolver): ?string;
    /**
     * Indicates if the directive argument names can be omitted from the query, deducing them from the order in which they were defined in the schema
     *
     * @param TypeResolverInterface $typeResolver
     * @param string $directive
     * @return boolean
     */
    public function enableOrderedSchemaDirectiveArgs(TypeResolverInterface $typeResolver): bool;
    /**
     * Schema Directive Arguments
     *
     * @param TypeResolverInterface $typeResolver
     * @return array
     */
    public function getSchemaDirectiveArgs(TypeResolverInterface $typeResolver): array;
    /**
     * Filtered Schema Directive Arguments
     *
     * @param TypeResolverInterface $typeResolver
     * @return array
     */
    public function getFilteredSchemaDirectiveArgs(TypeResolverInterface $typeResolver): array;
    /**
     * Expressions set by the directive
     *
     * @param TypeResolverInterface $typeResolver
     * @return string|null
     */
    public function getSchemaDirectiveExpressions(TypeResolverInterface $typeResolver): array;
    /**
     * Raise warnings concerning the directive
     *
     * @param TypeResolverInterface $typeResolver
     * @return string|null
     */
    public function getSchemaDirectiveWarningDescription(TypeResolverInterface $typeResolver): ?string;
    /**
     * Indicate if the directive has been deprecated, why, when, and/or how it must be replaced
     *
     * @param TypeResolverInterface $typeResolver
     * @return string|null
     */
    public function getSchemaDirectiveDeprecationDescription(TypeResolverInterface $typeResolver): ?string;
    /**
     * Indicate if the directive is global (i.e. it can be applied to all fields, for all typeResolvers)
     *
     * @param TypeResolverInterface $typeResolver
     * @return bool
     */
    public function isGlobal(TypeResolverInterface $typeResolver): bool;
}
