<?php

declare(strict_types=1);

namespace PoPSchema\UserMeta\TypeAPIs;

use InvalidArgumentException;

interface UserMetaTypeAPIInterface
{
    /**
     * If the allow/denylist validation fails, throw an exception.
     * If the key is allowed but non-existent, return `null`.
     * Otherwise, return the value.
     *
     * @throws InvalidArgumentException
     */
    public function getUserMeta(string | int $userID, string $key, bool $single = false): mixed;
}
