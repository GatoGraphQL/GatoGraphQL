<?php

declare(strict_types=1);

namespace PoP\ComponentModel\QueryResolution;

use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\Exception\ShouldNotHappenException;

interface FieldDataAccessProviderInterface
{
    /**
     * @return array<string,mixed>
     * @throws ShouldNotHappenException If accessing a non-set Field/ObjectTypeResolver/object
     */
    public function getFieldData(
        FieldInterface $field,
        ?ObjectTypeResolverInterface $objectTypeResolver = null,
        ?object $object = null,
    ): array;
}
