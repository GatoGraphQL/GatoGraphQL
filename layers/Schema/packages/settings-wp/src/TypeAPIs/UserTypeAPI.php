<?php

declare(strict_types=1);

namespace PoPSchema\SettingsWP\TypeAPIs;

use WP_User;
use PoPSchema\Settings\TypeAPIs\UserTypeAPIInterface;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class UserTypeAPI implements UserTypeAPIInterface
{
    /**
     * Indicates if the passed object is of type User
     */
    public function isInstanceOfUserType(object $object): bool
    {
        return $object instanceof WP_User;
    }
}
