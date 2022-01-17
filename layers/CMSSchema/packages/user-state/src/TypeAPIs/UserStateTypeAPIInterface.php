<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserState\TypeAPIs;

interface UserStateTypeAPIInterface
{
    public function isUserLoggedIn(): bool;
    public function getCurrentUser(): ?object;
    public function getCurrentUserID(): string|int|null;
}
