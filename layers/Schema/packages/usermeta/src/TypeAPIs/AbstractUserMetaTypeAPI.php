<?php

declare(strict_types=1);

namespace PoPSchema\UserMeta\TypeAPIs;

use InvalidArgumentException;
use PoPSchema\Meta\TypeAPIs\AbstractMetaTypeAPI;
use PoPSchema\UserMeta\ComponentConfiguration;

abstract class AbstractUserMetaTypeAPI extends AbstractMetaTypeAPI implements UserMetaTypeAPIInterface
{
    /**
     * If the allow/denylist validation fails, and passing option "assert-is-meta-key-allowed",
     * then throw an exception.
     * If the key is allowed but non-existent, return `null`.
     * Otherwise, return the value.
     *
     * @param array<string,mixed> $options
     * @throws InvalidArgumentException
     */
    final public function getUserMeta(string | int $userID, string $key, bool $single = false, array $options = []): mixed
    {
        if ($options['assert-is-meta-key-allowed'] ?? null) {
            $this->assertIsMetaKeyAllowed($key);
        }
        return $this->doGetUserMeta($userID, $key, $single);
    }

    /**
     * @return string[]
     */
    public function getAllowOrDenyMetaEntries(): array
    {
        return ComponentConfiguration::getUserMetaEntries();
    }
    public function getAllowOrDenyMetaBehavior(): string
    {
        return ComponentConfiguration::getUserMetaBehavior();
    }

    /**
     * If the key is non-existent, return `null`.
     * Otherwise, return the value.
     */
    abstract protected function doGetUserMeta(string | int $userID, string $key, bool $single = false): mixed;
}
