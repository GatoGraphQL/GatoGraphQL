<?php

declare(strict_types=1);

namespace PoPSitesWassup\UserStateMutations\MutationResolvers;

use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\UserAccount\FunctionAPIFactory;
use PoPCMSSchema\UserStateMutations\MutationResolvers\LoginUserByCredentialsMutationResolver as UpstreamLoginUserByCredentialsMutationResolver;

class LoginUserByCredentialsMutationResolver extends UpstreamLoginUserByCredentialsMutationResolver
{
    protected function getUserAlreadyLoggedInError(string | int $user_id): FeedbackItemResolution
    {
        $cmsuseraccountapi = FunctionAPIFactory::getInstance();
        // @todo Migrate from string to FeedbackItemProvider
        // $errors[] = new FeedbackItemResolution(
        //     MutationErrorFeedbackItemProvider::class,
        //     MutationErrorFeedbackItemProvider::E1,
        // );
        return sprintf(
            $this->__('You are already logged in as <a href="%s">%s</a>, <a href="%s">logout</a>?', 'user-state-mutations'),
            $this->getUserTypeAPI()->getUserURL($user_id),
            $this->getUserTypeAPI()->getUserDisplayName($user_id),
            $cmsuseraccountapi->getLogoutURL()
        );
    }
}
