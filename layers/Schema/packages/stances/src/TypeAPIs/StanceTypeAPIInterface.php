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
     *
     * @param [type] $stance
     * @return void
     */
    public function getID($stance);
    /**
     * Indicates if the passed object is of type Stance
     *
     * @param [type] $object
     * @return boolean
     */
    public function isInstanceOfStanceType($object): bool;
    /**
     * Get the stance with provided ID or, if it doesn't exist, null
     *
     * @param [type] $id
     * @return void
     */
    public function getStance($id);
    /**
     * Indicate if an stance with provided ID exists
     *
     * @param [type] $id
     * @return void
     */
    public function stanceExists($id): bool;
}
