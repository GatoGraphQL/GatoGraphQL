<?php

declare(strict_types=1);

namespace PoPSchema\UserMeta\TypeAPIs;

use PoPSchema\UserMeta\ComponentConfiguration;
use PoPSchema\UserMeta\TypeAPIs\UserMetaTypeAPIInterface;
use PoPSchema\SchemaCommons\Facades\Services\AllowOrDenySettingsServiceFacade;

abstract class AbstractUserMetaTypeAPI implements UserMetaTypeAPIInterface
{
    final public function getUserMeta(string | int $userID, string $key, bool $single = false): mixed
    {
        /**
         * Check if the allow/denylist validation fails
         * Compare for full match or regex
         */
        $entries = ComponentConfiguration::getUserMetaEntries();
        $behavior = ComponentConfiguration::getUserMetaBehavior();
        $allowOrDenySettingsService = AllowOrDenySettingsServiceFacade::getInstance();
        if (!$allowOrDenySettingsService->isEntryAllowed($key, $entries, $behavior)) {
            return null;
        }
        return $this->doGetUserMeta($userID, $key, $single);
    }

    abstract protected function doGetUserMeta(string | int $userID, string $key, bool $single = false): mixed;
}
