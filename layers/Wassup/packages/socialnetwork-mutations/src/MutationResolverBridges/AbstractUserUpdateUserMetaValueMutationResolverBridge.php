<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolverBridges;

use PoPCMSSchema\Users\Constants\InputNames;
use PoPCMSSchema\Users\TypeAPIs\UserTypeAPIInterface;

abstract class AbstractUserUpdateUserMetaValueMutationResolverBridge extends AbstractUpdateUserMetaValueMutationResolverBridge
{
    private ?UserTypeAPIInterface $userTypeAPI = null;

    final public function setUserTypeAPI(UserTypeAPIInterface $userTypeAPI): void
    {
        $this->userTypeAPI = $userTypeAPI;
    }
    final protected function getUserTypeAPI(): UserTypeAPIInterface
    {
        /** @var UserTypeAPIInterface */
        return $this->userTypeAPI ??= $this->instanceManager->getInstance(UserTypeAPIInterface::class);
    }

    protected function getRequestKey(): string
    {
        return InputNames::USER_ID;
    }
}
