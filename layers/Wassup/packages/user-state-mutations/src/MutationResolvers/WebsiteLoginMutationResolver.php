<?php

declare(strict_types=1);

namespace PoPSitesWassup\UserStateMutations\MutationResolvers;

use PoP\UserAccount\FunctionAPIFactory;
use PoPSchema\UserStateMutations\MutationResolvers\LoginUserByCredentialsMutationResolver as UpstreamLoginUserByCredentialsMutationResolver;

class LoginUserByCredentialsMutationResolver extends UpstreamLoginUserByCredentialsMutationResolver
{
    protected function getUserAlreadyLoggedInErrorMessage(string | int $user_id): string
    {
        $cmsuseraccountapi = FunctionAPIFactory::getInstance();
        return sprintf(
            $this->__('You are already logged in as <a href="%s">%s</a>, <a href="%s">logout</a>?', 'user-state-mutations'),
            $this->getUserTypeAPI()->getUserURL($user_id),
            $this->getUserTypeAPI()->getUserDisplayName($user_id),
            $cmsuseraccountapi->getLogoutURL()
        );
    }
}
