<?php

declare(strict_types=1);

namespace PoPSchema\UserAvatarsWP\TypeAPIs;

use PoPSchema\UserAvatars\TypeAPIs\AbstractUserAvatarTypeAPI;

class UserAvatarTypeAPI extends AbstractUserAvatarTypeAPI
{
    /**
     * @return array<string,mixed>
     */
    public function getUserAvatarData(string | int | object $userObjectOrID): array
    {
        return [
            'src' => '',
        ];
    }
}
