<?php

declare(strict_types=1);

namespace PoPSitesWassup\UserStateMutations\MutationResolvers;

use PoP\UserAccount\FunctionAPIFactory;
use PoPSchema\Users\TypeAPIs\UserTypeAPIInterface;
use PoPSchema\UserStateMutations\MutationResolvers\LoginMutationResolver as UpstreamLoginMutationResolver;
use Symfony\Contracts\Service\Attribute\Required;

class LoginMutationResolver extends UpstreamLoginMutationResolver
{
    private ?UserTypeAPIInterface $userTypeAPI = null;

    public function setUserTypeAPI(UserTypeAPIInterface $userTypeAPI): void
    {
        $this->userTypeAPI = $userTypeAPI;
    }
    protected function getUserTypeAPI(): UserTypeAPIInterface
    {
        return $this->userTypeAPI ??= $this->instanceManager->getInstance(UserTypeAPIInterface::class);
    }

    //#[Required]
    final public function autowireUserStateMutationsLoginMutationResolver(
        UserTypeAPIInterface $userTypeAPI,
    ): void {
        $this->userTypeAPI = $userTypeAPI;
    }

    protected function getUserAlreadyLoggedInErrorMessage(string | int $user_id): string
    {
        $cmsuseraccountapi = FunctionAPIFactory::getInstance();
        return sprintf(
            $this->translationAPI->__('You are already logged in as <a href="%s">%s</a>, <a href="%s">logout</a>?', 'user-state-mutations'),
            $this->getUserTypeAPI()->getUserURL($user_id),
            $this->getUserTypeAPI()->getUserDisplayName($user_id),
            $cmsuseraccountapi->getLogoutURL()
        );
    }
}
