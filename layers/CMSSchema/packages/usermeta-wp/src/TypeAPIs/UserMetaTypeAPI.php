<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaWP\TypeAPIs;

use PoPCMSSchema\MetaQueryWP\TypeAPIs\ProtectedMetaKeyResolverTrait;
use PoPCMSSchema\UserMeta\TypeAPIs\AbstractUserMetaTypeAPI;
use WP_User;

use function current_user_can;
use function get_user_meta;
use function is_protected_meta;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class UserMetaTypeAPI extends AbstractUserMetaTypeAPI
{
    use ProtectedMetaKeyResolverTrait;

    public function isMetaKeyProtected(string $key): bool
    {
        if ($this->isMetaKeyRoleRelated($key)) {
            return !current_user_can('promote_users');
        }
        if (current_user_can('manage_options')) {
            return false;
        }
        if ($this->isMetaKeyAbsolutelyProtected($key)) {
            return true;
        }
        if ($this->isMetaKeyProtectedByCMS($key)) {
            return !$this->isMetaKeyExplicitlyAllowed($key);
        }
        return false;
    }

    protected function isMetaKeyProtectedByCMS(string $key): bool
    {
        return $this->matchesProtectedMetaKey(
            $key,
            fn (string $candidateKey): bool => is_protected_meta($candidateKey, 'user')
        );
    }

    protected function isMetaKeyAbsolutelyProtected(string $key): bool
    {
        return $this->matchesProtectedMetaKey(
            $key,
            fn (string $candidateKey): bool => $this->isNormalizedMetaKeyAbsolutelyProtected(
                $this->normalizeMetaKeyForProtection($candidateKey)
            )
        );
    }

    protected function isMetaKeyRoleRelated(string $key): bool
    {
        return $this->matchesProtectedMetaKey(
            $key,
            fn (string $candidateKey): bool => $this->isNormalizedMetaKeyRoleRelated(
                $this->normalizeMetaKeyForProtection($candidateKey)
            )
        );
    }

    protected function isNormalizedMetaKeyRoleRelated(string $normalizedKey): bool
    {
        global $wpdb;
        $basePrefix = strtolower($wpdb->base_prefix);
        return preg_match(
            '/^' . preg_quote($basePrefix, '/') . '(?:\d+_)?(?:capabilities|user_level)$/',
            $normalizedKey
        ) === 1;
    }

    protected function isNormalizedMetaKeyAbsolutelyProtected(string $normalizedKey): bool
    {
        return $normalizedKey === 'session_tokens'
            || $normalizedKey === '_application_passwords'
            || $this->isNormalizedMetaKeyRoleRelated($normalizedKey);
    }

    protected function getMetaDatabaseTableName(): string
    {
        global $wpdb;
        return $wpdb->usermeta;
    }

    public function isMetaKeyProtectedFromReading(string $key): bool
    {
        return $this->isMetaKeyProtected($key);
    }

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

        $meta = get_user_meta((int)$userID) ?? [];
        if (!is_array($meta)) {
            return [];
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
            $meta
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
