<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Exception;

use PoP\GraphQLParser\Spec\Parser\Location;
use Throwable;

interface LocationableExceptionInterface extends Throwable
{
    public function getLocation(): Location;
}
