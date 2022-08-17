<?php

declare(strict_types=1);

namespace PoP\ComponentModel\HelperServices;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

interface DataloadHelperServiceInterface
{
    /**
     * Accept RelationalTypeResolverInterface as param, instead of the more natural
     * ObjectTypeResolverInterface, to make it easy within the application to check
     * for this result without checking in advance what's the typeResolver.
     */
    public function getTypeResolverFromSubcomponentField(
        RelationalTypeResolverInterface $relationalTypeResolver,
        FieldInterface $field,
    ): ?RelationalTypeResolverInterface;
}
