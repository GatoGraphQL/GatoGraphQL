<?php

declare(strict_types=1);

namespace PoPSchema\Stances\TypeAPIs;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface StanceTypeAPIInterface
{
    /**
     * Return the stance's ID
     */
    public function getID(object $stance): string|int;
    /**
     * Indicates if the passed object is of type Stance
     */
    public function isInstanceOfStanceType(object $object): bool;
    /**
     * Get the stance with provided ID or, if it doesn't exist, null
     */
    public function getStance(int|string $id): ?object;
    /**
     * Indicate if an stance with provided ID exists
     */
    public function stanceExists(int|string $id): bool;
}
