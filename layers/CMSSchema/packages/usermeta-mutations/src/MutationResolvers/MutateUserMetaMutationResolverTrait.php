<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\MutationResolvers;

use PoPCMSSchema\MetaMutations\MutationResolvers\MutateEntityMetaMutationResolverTrait;
use PoPCMSSchema\Meta\TypeAPIs\MetaTypeAPIInterface;
use PoPCMSSchema\UserMeta\TypeAPIs\UserMetaTypeAPIInterface;

trait MutateUserMetaMutationResolverTrait
{
    use MutateEntityMetaMutationResolverTrait;

    abstract protected function getUserMetaTypeAPI(): UserMetaTypeAPIInterface;

    protected function getMetaTypeAPI(): MetaTypeAPIInterface
    {
        return $this->getUserMetaTypeAPI();
    }

    protected function doesMetaEntryExist(
        string|int $entityID,
        string $key,
    ): bool {
        return $this->getUserMetaTypeAPI()->getUserMeta($entityID, $key, true) !== null;
    }

    protected function doesMetaEntryWithValueExist(
        string|int $entityID,
        string $key,
        mixed $value,
    ): bool {
        return in_array($value, $this->getUserMetaTypeAPI()->getUserMeta($entityID, $key, false));
    }
}
