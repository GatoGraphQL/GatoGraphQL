<?php

declare(strict_types=1);

namespace PoPSchema\UserMeta\TypeAPIs;

use PoP\ComponentModel\Services\BasicServiceTrait;
use PoPSchema\SchemaCommons\Services\AllowOrDenySettingsServiceInterface;
use PoPSchema\UserMeta\ComponentConfiguration;

abstract class AbstractUserMetaTypeAPI implements UserMetaTypeAPIInterface
{
    use BasicServiceTrait;

    private ?AllowOrDenySettingsServiceInterface $allowOrDenySettingsService = null;

    final public function setAllowOrDenySettingsService(AllowOrDenySettingsServiceInterface $allowOrDenySettingsService): void
    {
        $this->allowOrDenySettingsService = $allowOrDenySettingsService;
    }
    final protected function getAllowOrDenySettingsService(): AllowOrDenySettingsServiceInterface
    {
        return $this->allowOrDenySettingsService ??= $this->instanceManager->getInstance(AllowOrDenySettingsServiceInterface::class);
    }

    final public function getUserMeta(string | int $userID, string $key, bool $single = false): mixed
    {
        /**
         * Check if the allow/denylist validation fails
         * Compare for full match or regex
         */
        $entries = ComponentConfiguration::getUserMetaEntries();
        $behavior = ComponentConfiguration::getUserMetaBehavior();
        if (!$this->getAllowOrDenySettingsService()->isEntryAllowed($key, $entries, $behavior)) {
            return null;
        }
        return $this->doGetUserMeta($userID, $key, $single);
    }

    abstract protected function doGetUserMeta(string | int $userID, string $key, bool $single = false): mixed;
}
