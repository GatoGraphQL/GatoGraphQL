<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers;

abstract class AbstractFieldResolver implements FieldResolverInterface
{
    public function getAdminFieldNames(): array
    {
        return [];
    }
}
