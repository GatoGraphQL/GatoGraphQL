<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMeta\TypeAPIs;

use PoPCMSSchema\Meta\Exception\MetaKeyNotAllowedException;
use PoPCMSSchema\Meta\TypeAPIs\MetaTypeAPIInterface;

interface UserMetaTypeAPIInterface extends MetaTypeAPIInterface
{
    /**
     * If the allow/denylist validation fails, and passing option "assert-is-meta-key-allowed",
     * then throw an exception.
     * If the key is allowed but non-existent, return `null`.
     * Otherwise, return the value.
     *
     * @param array<string,mixed> $options
     * @throws MetaKeyNotAllowedException
     */
    public function getUserMeta(string|int|object $userObjectOrID, string $key, bool $single = false, array $options = []): mixed;

    /**
     * @return array<string,mixed>
     */
    public function getAllUserMeta(string|int|object $userObjectOrID): array;

    /**
     * @return string[]
     */
    public function getUserMetaKeys(string|int|object $userObjectOrID): array;
}
