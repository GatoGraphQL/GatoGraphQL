<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\TypeAPIs;

interface UserTypeMutationAPIInterface
{
    public function canLoggedInUserEditUser(string|int $userID): bool;
}
