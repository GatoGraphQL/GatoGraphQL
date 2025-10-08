<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaWP\TypeAPIs;

use PoPCMSSchema\UserMeta\TypeAPIs\AbstractUserMetaTypeAPI;
use WP_User;

use function get_user_meta;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class UserMetaTypeAPI extends AbstractUserMetaTypeAPI
{
    /**
     * If the key is non-existent, return `null`.
     * Otherwise, return the value.
     */
    protected function doGetUserMeta(string|int|object $userObjectOrID, string $key, bool $single = false): mixed
    {
        if (is_object($userObjectOrID)) {
            /** @var WP_User */
            $user = $userObjectOrID;
            $userID = $user->ID;
        } else {
            $userID = $userObjectOrID;
        }

        /**
         * This function does not differentiate between a stored empty value,
         * and a non-existing key!
         *
         * So if empty, treat it as non-existent and return null.
         */
        $value = get_user_meta((int)$userID, $key, $single);
        if (($single && $value === '') || (!$single && $value === [])) {
            return null;
        }
        return $value;
    }

    /**
     * @return array<string,mixed>
     */
    public function getAllUserMeta(string|int|object $userObjectOrID): array
    {
        if (is_object($userObjectOrID)) {
            /** @var WP_User */
            $user = $userObjectOrID;
            $userID = $user->ID;
        } else {
            $userID = $userObjectOrID;
        }

        return array_map(
            /**
             * @param mixed[] $items
             * @return mixed[]
             */
            function (array $items): array {
                return array_map(
                    \maybe_unserialize(...),
                    $items
                );
            },
            get_user_meta((int)$userID) ?? []
        );
    }

    /**
     * @return string[]
     */
    public function getUserMetaKeys(string|int|object $userObjectOrID): array
    {
        return array_keys($this->getAllUserMeta($userObjectOrID));
    }
}
