<?php

declare(strict_types=1);

namespace PoP\ComponentModel\HelperServices;

use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\RelationalComponentField;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

interface DataloadHelperServiceInterface
{
    public function getTypeResolverFromSubcomponentDataField(RelationalTypeResolverInterface $relationalTypeResolver, RelationalComponentField $relationalComponentField): ?RelationalTypeResolverInterface;
}
