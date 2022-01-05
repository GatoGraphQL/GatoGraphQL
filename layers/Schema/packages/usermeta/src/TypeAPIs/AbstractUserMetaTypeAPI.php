<?php

declare(strict_types=1);

namespace PoPSchema\UserMeta\TypeAPIs;

use PoP\Root\Managers\ComponentManager;
use InvalidArgumentException;
use PoPSchema\Meta\TypeAPIs\AbstractMetaTypeAPI;
use PoPSchema\UserMeta\Component;
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
        /** @var ComponentConfiguration */
        $componentConfiguration = \PoP\Engine\App::getComponentManager()->getComponent(Component::class)->getConfiguration();
        return $componentConfiguration->getUserMetaEntries();
    }
    public function getAllowOrDenyMetaBehavior(): string
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = \PoP\Engine\App::getComponentManager()->getComponent(Component::class)->getConfiguration();
        return $componentConfiguration->getUserMetaBehavior();
    }

    /**
     * If the key is non-existent, return `null`.
     * Otherwise, return the value.
     */
    abstract protected function doGetUserMeta(string | int $userID, string $key, bool $single = false): mixed;
}
