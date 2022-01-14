<?php

declare(strict_types=1);

namespace PoPSchema\UserState\TypeAPIs;

/**
 * Default "mock" service returning always a void response.
 * It must be overriden for the specific CMS.
 *
 * This service is already injected to make PHPUnit work.
 */
class VoidUserStateTypeAPI implements UserStateTypeAPIInterface
{
    public function isUserLoggedIn(): bool
    {
        return false;
    }
    public function getCurrentUser(): ?object
    {
        return null;
    }
    public function getCurrentUserID(): string|int|null
    {
        return null;
    }
}
