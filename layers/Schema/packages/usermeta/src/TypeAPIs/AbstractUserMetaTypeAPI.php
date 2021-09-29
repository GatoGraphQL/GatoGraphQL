<?php

declare(strict_types=1);

namespace PoPSchema\UserMeta\TypeAPIs;

use Symfony\Contracts\Service\Attribute\Required;
use PoPSchema\SchemaCommons\Services\AllowOrDenySettingsServiceInterface;
use PoPSchema\UserMeta\ComponentConfiguration;

abstract class AbstractUserMetaTypeAPI implements UserMetaTypeAPIInterface
{
    protected AllowOrDenySettingsServiceInterface $allowOrDenySettingsService;

    #[Required]
    public function autowireAbstractUserMetaTypeAPI(AllowOrDenySettingsServiceInterface $allowOrDenySettingsService): void
    {
        $this->allowOrDenySettingsService = $allowOrDenySettingsService;
    }

    final public function getUserMeta(string | int $userID, string $key, bool $single = false): mixed
    {
        /**
         * Check if the allow/denylist validation fails
         * Compare for full match or regex
         */
        $entries = ComponentConfiguration::getUserMetaEntries();
        $behavior = ComponentConfiguration::getUserMetaBehavior();
        if (!$this->allowOrDenySettingsService->isEntryAllowed($key, $entries, $behavior)) {
            return null;
        }
        return $this->doGetUserMeta($userID, $key, $single);
    }

    abstract protected function doGetUserMeta(string | int $userID, string $key, bool $single = false): mixed;
}
