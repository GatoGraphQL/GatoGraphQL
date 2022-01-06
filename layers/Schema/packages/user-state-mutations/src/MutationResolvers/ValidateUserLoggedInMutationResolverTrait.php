<?php

declare(strict_types=1);

namespace PoPSchema\UserStateMutations\MutationResolvers;

use PoP\ComponentModel\State\ApplicationState;
use PoP\Translation\TranslationAPIInterface;

trait ValidateUserLoggedInMutationResolverTrait
{
    abstract protected function getTranslationAPI(): TranslationAPIInterface;

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
        return $this->__('You are not logged in', 'user-state-mutations');
    }
}
