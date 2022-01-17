<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaWP\TypeAPIs;

use PoPCMSSchema\UserMeta\TypeAPIs\AbstractUserMetaTypeAPI;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class UserMetaTypeAPI extends AbstractUserMetaTypeAPI
{
    /**
     * If the key is non-existent, return `null`.
     * Otherwise, return the value.
     */
    protected function doGetUserMeta(string | int $userID, string $key, bool $single = false): mixed
    {
        // This function does not differentiate between a stored empty value,
        // and a non-existing key! So if empty, treat it as non-existant and return null
        $value = \get_user_meta($userID, $key, $single);
        if (($single && $value === '') || (!$single && $value === [])) {
            return null;
        }
        return $value;
    }
}
