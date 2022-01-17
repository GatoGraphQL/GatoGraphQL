<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserAvatarsWP\TypeAPIs;

use PoP\Root\Services\BasicServiceTrait;
use PoPCMSSchema\UserAvatars\TypeAPIs\UserAvatarTypeAPIInterface;
use WP_User;

class UserAvatarTypeAPI implements UserAvatarTypeAPIInterface
{
    use BasicServiceTrait;

    protected function getUserID(string | int | object $userObjectOrID): string | int
    {
        if (is_object($userObjectOrID)) {
            /** @var WP_User */
            $user = $userObjectOrID;
            return $user->ID;
        }
        return $userObjectOrID;
    }

    public function getUserAvatarSrc(string | int | object $userObjectOrID, int $size = 150): ?string
    {
        $avatarHTML = \get_avatar($this->getUserID($userObjectOrID), $size);
        if ($avatarHTML === false) {
            return null;
        }
        // Extract attribute "src" from the <img> HTML tag
        // It can be src="..." or src='...'
        $matches = array();
        preg_match('/ src=["|\']([^"|\']*)["|\']/i', $avatarHTML, $matches);
        return $matches[1] ?? null;
    }
}
