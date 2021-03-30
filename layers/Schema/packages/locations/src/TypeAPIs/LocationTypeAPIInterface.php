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
     */
    public function getID(object $location): string | int;
    /**
     * Indicates if the passed object is of type Location
     */
    public function isInstanceOfLocationType(object $object): bool;
    public function getLocation(string | int $location_id): object;
    public function getLocations($args = array(), array $options = []): array;
    public function getLatitude(object $location): int;
    public function getLongitude(object $location): int;
    public function getName(object $location): string;
    public function getAddress(object $location): string;
    public function getCity(object $location): string;
}
