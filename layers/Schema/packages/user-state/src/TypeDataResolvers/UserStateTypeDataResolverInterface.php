<?php

declare(strict_types=1);

namespace PoPSchema\UserState\TypeDataResolvers;

interface UserStateTypeDataResolverInterface
{
    public function isUserLoggedIn(): bool;
    public function getCurrentUser();
    public function getCurrentUserID();
}
