<?php

declare(strict_types=1);

namespace PoPSchema\UserStateMutations\MutationResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\State\ApplicationState;

trait ValidateUserLoggedInMutationResolverTrait
{
    /**
     * Check that the user is logged-in
     *
     * @param string[] $errors
     */
    protected function validateUserIsLoggedIn(array &$errors): void
    {
        $vars = ApplicationState::getVars();
        if (!$vars['global-userstate']['is-user-logged-in']) {
            $errors[] = $this->getUserNotLoggedInErrorMessage();
        }
    }
    protected function getUserNotLoggedInErrorMessage(): string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return $translationAPI->__('You are not logged in', 'user-state-mutations');
    }
}
