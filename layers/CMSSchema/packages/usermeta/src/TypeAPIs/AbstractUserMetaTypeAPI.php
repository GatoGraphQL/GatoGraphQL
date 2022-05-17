<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMeta\TypeAPIs;

use PoP\Root\App;
use PoPCMSSchema\Meta\Exception\MetaKeyNotAllowedException;
use PoPCMSSchema\Meta\TypeAPIs\AbstractMetaTypeAPI;
use PoPCMSSchema\UserMeta\Module;
use PoPCMSSchema\UserMeta\ModuleConfiguration;

abstract class AbstractUserMetaTypeAPI extends AbstractMetaTypeAPI implements UserMetaTypeAPIInterface
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
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getComponent(Module::class)->getConfiguration();
        return $moduleConfiguration->getUserMetaEntries();
    }
    public function getAllowOrDenyMetaBehavior(): string
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getComponent(Module::class)->getConfiguration();
        return $moduleConfiguration->getUserMetaBehavior();
    }

    /**
     * If the key is non-existent, return `null`.
     * Otherwise, return the value.
     */
    abstract protected function doGetUserMeta(string | int $userID, string $key, bool $single = false): mixed;
}
