<?php

declare(strict_types=1);

namespace PoPSchema\Settings\TypeAPIs;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface UserTypeAPIInterface
{
    /**
     * Indicates if the passed object is of type User
     */
    public function isInstanceOfUserType(object $object): bool;
}
