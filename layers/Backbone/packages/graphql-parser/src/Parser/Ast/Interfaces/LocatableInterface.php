<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Parser\Ast\Interfaces;

use PoPBackbone\GraphQLParser\Parser\Location;

interface LocatableInterface
{
    public function getLocation(): Location;
}
