<?php

declare(strict_types=1);

namespace PoPSchema\UserMetaWP\TypeAPIs;

use PoPSchema\UserMeta\TypeAPIs\AbstractUserMetaTypeAPI;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class UserMetaTypeAPI extends AbstractUserMetaTypeAPI
{
    /**
     * If the key is non-existent, return `null`.
     * Otherwise, return the value.
     */
    public function doGetUserMeta(string | int $userID, string $key, bool $single = false): mixed
    {
        $value = \get_user_meta($userID, $key, $single);
        if ($value === '') {
            return null;
        }
        return $value;
    }
}
