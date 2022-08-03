<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Exception;

use PoP\GraphQLParser\Spec\Parser\Location;

interface LocationableExceptionInterface
{
    public function getLocation(): Location;
}
