<?php

/**
 * Date: 16.11.16
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace PoP\GraphQLParser\Exception\Interfaces;

use PoP\GraphQLParser\Parser\Location;

interface LocationableExceptionInterface
{

    /**
     * @return Location
     */
    public function getLocation();
}
