<?php

declare(strict_types=1);

namespace PoPSchema\UserMeta\TypeAPIs;

use InvalidArgumentException;
use PoPSchema\Meta\TypeAPIs\AbstractMetaTypeAPI;
use PoPSchema\UserMeta\ComponentConfiguration;

abstract class AbstractUserMetaTypeAPI extends AbstractMetaTypeAPI implements UserMetaTypeAPIInterface
{
    /**
     * If the allow/denylist validation fails, throw an exception
     *
     * @throws InvalidArgumentException
     */
    final public function getUserMeta(string | int $userID, string $key, bool $single = false): mixed
    {
        $entries = ComponentConfiguration::getUserMetaEntries();
        $behavior = ComponentConfiguration::getUserMetaBehavior();
        $this->assertIsEntryAllowed($entries, $behavior, $key);
        return $this->doGetUserMeta($userID, $key, $single);
    }

    abstract protected function doGetUserMeta(string | int $userID, string $key, bool $single = false): mixed;
}
