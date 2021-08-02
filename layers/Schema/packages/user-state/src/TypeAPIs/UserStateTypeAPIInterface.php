<?php

declare(strict_types=1);

namespace PoPSchema\UserState\TypeAPIs;

interface UserStateTypeAPIInterface
{
    public function isUserLoggedIn(): bool;
    public function getCurrentUser();
    public function getCurrentUserID();
}
