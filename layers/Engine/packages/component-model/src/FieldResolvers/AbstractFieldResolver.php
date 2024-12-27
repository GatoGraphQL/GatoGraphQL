<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers;

use PoP\Root\Services\AbstractBasicService;

abstract class AbstractFieldResolver extends AbstractBasicService implements FieldResolverInterface
{
    /**
     * @return string[]
     */
    public function getSensitiveFieldNames(): array
    {
        return [];
    }
}
