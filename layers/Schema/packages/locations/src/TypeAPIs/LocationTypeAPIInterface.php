<?php

declare(strict_types=1);

namespace PoPSchema\Locations\TypeAPIs;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface LocationTypeAPIInterface
{
    /**
     * Return the location's ID
     *
     * @param [type] $location
     * @return void
     */
    public function getID($location);
    /**
     * Indicates if the passed object is of type Location
     *
     * @param [type] $object
     * @return boolean
     */
    public function isInstanceOfLocationType($object): bool;
}
