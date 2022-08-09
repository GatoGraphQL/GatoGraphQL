<?php

declare(strict_types=1);

namespace PoP\ComponentModel\QueryResolution;

use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\Exception\ShouldNotHappenException;

interface FieldDataAccessProviderInterface
{
    /**
     * @return array<string,mixed>|null null if casting the fieldArgs produced an error
     * @throws ShouldNotHappenException
     */
    public function getFieldArgs(
        FieldInterface $field,
        ?ObjectTypeResolverInterface $objectTypeResolver = null,
        ?object $object = null,
    ): ?array;
    /**
     * Used by the nested directive resolver
     */
    public function duplicateFieldData(
        FieldInterface $fromField,
        FieldInterface $toField,
    ): void;
}
