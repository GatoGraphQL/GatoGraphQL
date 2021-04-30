<?php

declare(strict_types=1);

namespace PoPSitesWassup\UserStateMutations\MutationResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;

class LoginMutationResolver extends \PoPSchema\UserStateMutations\MutationResolvers\LoginMutationResolver
{
    protected function getUserAlreadyLoggedInErrorMessage(string | int $user_id): string
    {
        $cmsusersapi = \PoPSchema\Users\FunctionAPIFactory::getInstance();
        $cmsuseraccountapi = \PoP\UserAccount\FunctionAPIFactory::getInstance();
        return sprintf(
            $this->translationAPI->__('You are already logged in as <a href="%s">%s</a>, <a href="%s">logout</a>?', 'user-state-mutations'),
            $cmsusersapi->getUserURL($user_id),
            $cmsusersapi->getUserDisplayName($user_id),
            $cmsuseraccountapi->getLogoutURL()
        );
    }
}
