<?php

declare(strict_types=1);

namespace PoPSchema\UserAvatarsWP\TypeAPIs;

use PoPSchema\UserAvatars\TypeAPIs\AbstractUserAvatarTypeAPI;

class UserAvatarTypeAPI extends AbstractUserAvatarTypeAPI
{
    public function getUserAvatarSrc(string | int | object $userObjectOrID, int $size = 96): ?string
    {
        $avatarHTML = \get_avatar($userObjectOrID, $size);
        if ($avatarHTML === false) {
            return null;
        }
        // Extract the source from HTML <img> tag
        $matches = array();
        preg_match('/src="([^"]*)"/i', $avatarHTML , $matches);
        return $matches[1] ?? null;
    }
}
