<?php

/**
 * Date: 16.11.16
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace GraphQLByPoP\GraphQLParser\Exception\Interfaces;

use GraphQLByPoP\GraphQLParser\Parser\Location;

interface LocationableExceptionInterface
{

    /**
     * @return Location
     */
    public function getLocation();
}
