<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolverBridges;

use PoPSchema\Users\Constants\InputNames;
use PoPSchema\Users\TypeAPIs\UserTypeAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractUserUpdateUserMetaValueMutationResolverBridge extends AbstractUpdateUserMetaValueMutationResolverBridge
{
    protected UserTypeAPIInterface $userTypeAPI;

    #[Required]
    final public function autowireAbstractUserUpdateUserMetaValueMutationResolverBridge(
        UserTypeAPIInterface $userTypeAPI,
    ): void {
        $this->userTypeAPI = $userTypeAPI;
    }

    protected function getRequestKey()
    {
        return InputNames::USER_ID;
    }
}
