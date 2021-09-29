<?php

declare(strict_types=1);

namespace PoPSitesWassup\UserStateMutations\MutationResolvers;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\UserAccount\FunctionAPIFactory;
use PoPSchema\Users\TypeAPIs\UserTypeAPIInterface;
use PoPSchema\UserStateMutations\MutationResolvers\LoginMutationResolver as UpstreamLoginMutationResolver;

class LoginMutationResolver extends UpstreamLoginMutationResolver
{
    protected UserTypeAPIInterface $userTypeAPI;

    #[Required]
    public function autowireUserStateMutationsLoginMutationResolver(
        UserTypeAPIInterface $userTypeAPI,
    ): void {
        $this->userTypeAPI = $userTypeAPI;
    }

    protected function getUserAlreadyLoggedInErrorMessage(string | int $user_id): string
    {
        $cmsuseraccountapi = FunctionAPIFactory::getInstance();
        return sprintf(
            $this->translationAPI->__('You are already logged in as <a href="%s">%s</a>, <a href="%s">logout</a>?', 'user-state-mutations'),
            $this->userTypeAPI->getUserURL($user_id),
            $this->userTypeAPI->getUserDisplayName($user_id),
            $cmsuseraccountapi->getLogoutURL()
        );
    }
}
