<?php

declare(strict_types=1);

namespace PoPSchema\UserAvatars\TypeAPIs;

interface UserAvatarTypeAPIInterface
{
    /**
     * @return array<string,mixed>
     */
    public function getUserAvatarData(string | int | object $userObjectOrID): array;
}
