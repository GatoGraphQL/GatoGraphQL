<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers;

use PoP\ComponentModel\Services\BasicServiceTrait;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractFieldResolver implements FieldResolverInterface
{
    use BasicServiceTrait;

    public function getAdminFieldNames(): array
    {
        return [];
    }
}
