<?php

declare(strict_types=1);

namespace PoPSchema\UserMeta\TypeAPIs;

interface UserMetaTypeAPIInterface
{
    public function getUserMeta(string | int $userID, string $key, bool $single = false): mixed;
}
