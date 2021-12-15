<?php

/**
 * Date: 16.11.16
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace PoP\GraphQLParser\Parser\Ast\Interfaces;

use PoP\GraphQLParser\Parser\Location;

interface LocatableInterface
{
    public function getLocation(): Location;
}
