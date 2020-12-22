<?php

declare(strict_types=1);

namespace PoPSchema\Users\TypeAPIs;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface UserTypeAPIInterface
{
    /**
     * Indicates if the passed object is of type User
     *
     * @param [type] $object
     * @return boolean
     */
    public function isInstanceOfUserType($object): bool;
}
