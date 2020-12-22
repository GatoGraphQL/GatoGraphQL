<?php

declare(strict_types=1);

namespace PoPSchema\UserStateWP\TypeDataResolvers;

use PoPSchema\UserState\TypeDataResolvers\UserStateTypeDataResolverInterface;

class UserStateTypeDataResolver implements UserStateTypeDataResolverInterface
{

    public function isUserLoggedIn(): bool
    {
        return \is_user_logged_in();
    }
    public function getCurrentUser()
    {
        return \wp_get_current_user();
    }
    public function getCurrentUserID()
    {
        return \get_current_user_id();
    }
}
