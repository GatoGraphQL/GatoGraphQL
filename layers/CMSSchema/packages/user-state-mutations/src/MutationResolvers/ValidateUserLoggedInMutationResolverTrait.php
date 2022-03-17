<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateMutations\MutationResolvers;

use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\Root\App;
use PoPCMSSchema\UserStateMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;

trait ValidateUserLoggedInMutationResolverTrait
{
    /**
     * Check that the user is logged-in
     */
    protected function validateUserIsLoggedIn(): ?FeedbackItemResolution
    {
        if (!App::getState('is-user-logged-in')) {
            return $this->getUserNotLoggedInError();
        }
        return null;
    }
    protected function getUserNotLoggedInError(): FeedbackItemResolution
    {
        return new FeedbackItemResolution(
            MutationErrorFeedbackItemProvider::class,
            MutationErrorFeedbackItemProvider::E1,
        );
    }
}
