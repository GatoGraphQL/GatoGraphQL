<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\Hooks;

use PoPCMSSchema\UserMetaMutations\MutationResolvers\MutateUserMetaMutationResolverTrait;
use PoPCMSSchema\UserMetaMutations\TypeAPIs\UserMetaTypeMutationAPIInterface;
use PoPCMSSchema\UserMeta\TypeAPIs\UserMetaTypeAPIInterface;
use PoPCMSSchema\MetaMutations\Hooks\AbstractMetaMutationResolverHookSet;
use PoPCMSSchema\MetaMutations\TypeAPIs\EntityMetaTypeMutationAPIInterface;
use PoPCMSSchema\Meta\TypeAPIs\MetaTypeAPIInterface;

abstract class AbstractUserMetaMutationResolverHookSet extends AbstractMetaMutationResolverHookSet
{
    use MutateUserMetaMutationResolverTrait;

    private ?UserMetaTypeMutationAPIInterface $userMetaTypeMutationAPI = null;
    private ?UserMetaTypeAPIInterface $userMetaTypeAPI = null;

    final protected function getUserMetaTypeMutationAPI(): UserMetaTypeMutationAPIInterface
    {
        if ($this->userMetaTypeMutationAPI === null) {
            /** @var UserMetaTypeMutationAPIInterface */
            $userMetaTypeMutationAPI = $this->instanceManager->getInstance(UserMetaTypeMutationAPIInterface::class);
            $this->userMetaTypeMutationAPI = $userMetaTypeMutationAPI;
        }
        return $this->userMetaTypeMutationAPI;
    }
    final protected function getUserMetaTypeAPI(): UserMetaTypeAPIInterface
    {
        if ($this->userMetaTypeAPI === null) {
            /** @var UserMetaTypeAPIInterface */
            $userMetaTypeAPI = $this->instanceManager->getInstance(UserMetaTypeAPIInterface::class);
            $this->userMetaTypeAPI = $userMetaTypeAPI;
        }
        return $this->userMetaTypeAPI;
    }

    protected function getEntityMetaTypeMutationAPI(): EntityMetaTypeMutationAPIInterface
    {
        return $this->getUserMetaTypeMutationAPI();
    }

    protected function getMetaTypeAPI(): MetaTypeAPIInterface
    {
        return $this->getUserMetaTypeAPI();
    }

    // @todo Re-enable when adding User Mutations
    // protected function getErrorPayloadHookName(): string
    // {
    //     return UserCRUDHookNames::ERROR_PAYLOAD;
    // }
}
