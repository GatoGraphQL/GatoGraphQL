<?php

declare(strict_types=1);

namespace PoPSchema\UserMeta\TypeAPIs;

use InvalidArgumentException;

interface UserMetaTypeAPIInterface
{
    /**
     * If the allow/denylist validation fails, throw an exception
     *
     * @throws InvalidArgumentException
     */
    public function getUserMeta(string | int $userID, string $key, bool $single = false): mixed;
}
