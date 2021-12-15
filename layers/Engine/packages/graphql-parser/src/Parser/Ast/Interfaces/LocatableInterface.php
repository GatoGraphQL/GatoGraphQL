<?php

/**
 * Date: 16.11.16
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace GraphQLByPoP\GraphQLParser\Parser\Ast\Interfaces;

use GraphQLByPoP\GraphQLParser\Parser\Location;

interface LocatableInterface
{

    /**
     * @return Location
     */
    public function getLocation();
}
