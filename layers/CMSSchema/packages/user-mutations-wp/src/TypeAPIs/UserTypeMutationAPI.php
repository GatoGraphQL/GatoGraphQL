<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutationsWP\TypeAPIs;

use PoPCMSSchema\UserMutations\TypeAPIs\UserTypeMutationAPIInterface;
use PoP\ComponentModel\App;
use PoP\Root\Services\AbstractBasicService;

use function user_can;

class UserTypeMutationAPI extends AbstractBasicService implements UserTypeMutationAPIInterface
{
    public function canLoggedInUserEditUser(string|int $userID): bool
    {
        $loggedInUserID = App::getState('current-user-id');
        return ((int) $loggedInUserID === (int) $userID)
            || user_can((int)$loggedInUserID, 'edit_users');
    }
}
