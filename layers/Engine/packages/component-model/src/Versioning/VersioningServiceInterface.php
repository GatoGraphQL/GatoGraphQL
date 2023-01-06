<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Versioning;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

interface VersioningServiceInterface
{
    /**
     * Indicates the version constraints for specific fields in the schema
     */
    public function getVersionConstraintsForField(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldInterface $field,
    ): ?string;

    /**
     * Indicates the version constraints for specific directives in the schema
     */
    public function getVersionConstraintsForDirective(DirectiveResolverInterface $directive): ?string;
}
