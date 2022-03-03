<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateMutations\MutationResolvers;

use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\Root\App;
use PoP\Root\Translation\TranslationAPIInterface;
use PoPCMSSchema\SchemaCommons\FeedbackItemProviders\MutationErrorFeedbackItemProvider;

trait ValidateUserLoggedInMutationResolverTrait
{
    abstract protected function getTranslationAPI(): TranslationAPIInterface;

    /**
     * Check that the user is logged-in
     */
    protected function validateUserIsLoggedIn(): ?FeedbackItemResolution
    {
        if (!App::getState('is-user-logged-in')) {
            return $this->getUserNotLoggedInErrorMessage();
        }
        return null;
    }
    protected function getUserNotLoggedInErrorMessage(): FeedbackItemResolution
    {
        return new FeedbackItemResolution(
            MutationErrorFeedbackItemProvider::class,
            MutationErrorFeedbackItemProvider::E1,
        );
    }
}
