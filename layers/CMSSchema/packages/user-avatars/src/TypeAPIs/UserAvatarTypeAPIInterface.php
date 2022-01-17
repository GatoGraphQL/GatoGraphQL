<?php

declare(strict_types=1);

namespace PoPSchema\UserAvatars\TypeAPIs;

interface UserAvatarTypeAPIInterface
{
    public function getUserAvatarSrc(string | int | object $userObjectOrID, int $size = 150): ?string;
}
