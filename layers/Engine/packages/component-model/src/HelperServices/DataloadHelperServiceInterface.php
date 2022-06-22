<?php

declare(strict_types=1);

namespace PoP\ComponentModel\HelperServices;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

interface DataloadHelperServiceInterface
{
    public function getTypeResolverFromSubcomponentField(RelationalTypeResolverInterface $relationalTypeResolver, FieldInterface $field): ?RelationalTypeResolverInterface;
}
