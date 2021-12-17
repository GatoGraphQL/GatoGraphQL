<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers;

use PoP\BasicService\BasicServiceTrait;

abstract class AbstractFieldResolver implements FieldResolverInterface
{
    use BasicServiceTrait;

    public function getAdminFieldNames(): array
    {
        return [];
    }
}
