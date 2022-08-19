<?php

declare(strict_types=1);

namespace PoPSitesWassup\UserStateMutations\MutationResolverUtils;

class MutationResolverUtils
{
    public static function getLostPasswordCode($key, $user_login)
    {
        return $key . '|' . rawurlencode($user_login);
    }

    /**
     * @return array<string,string>
     */
    public static function decodeLostPasswordCode($code)
    {
        list($key, $user_login) = explode('|', stripslashes($code)/*wp_unslash($code)*/, 2);
        $user_login = rawurldecode($user_login);

        return array(
            'key' => $key,
            'login' => $user_login,
        );
    }
}
