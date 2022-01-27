<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Exception;

use PoPBackbone\GraphQLParser\Parser\Location;

interface LocationableExceptionInterface
{
    public function getLocation(): Location;
}
