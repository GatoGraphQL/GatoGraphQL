<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Parser\Ast\Interfaces;

use PoP\GraphQLParser\Parser\Location;

interface LocatableInterface
{
    public function getLocation(): Location;
}
