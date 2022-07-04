<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateMutations\MutationResolvers;

use PoP\ComponentModel\Mutation\FieldDataAccessorInterface;
use PoP\Root\Exception\AbstractException;
use PoP\Root\App;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoPCMSSchema\UserStateMutations\StaticHelpers\AppStateHelpers;
use PoPCMSSchema\UserStateMutations\TypeAPIs\UserStateTypeMutationAPIInterface;

class LogoutUserMutationResolver extends AbstractMutationResolver
{
    use ValidateUserLoggedInMutationResolverTrait;

    private ?UserStateTypeMutationAPIInterface $userStateTypeMutationAPI = null;

    final public function setUserStateTypeMutationAPI(UserStateTypeMutationAPIInterface $userStateTypeMutationAPI): void
    {
        $this->userStateTypeMutationAPI = $userStateTypeMutationAPI;
    }
    final protected function getUserStateTypeMutationAPI(): UserStateTypeMutationAPIInterface
    {
        return $this->userStateTypeMutationAPI ??= $this->instanceManager->getInstance(UserStateTypeMutationAPIInterface::class);
    }

    public function validateErrors(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        $errorFeedbackItemResolution = $this->validateUserIsLoggedIn();
        if ($errorFeedbackItemResolution !== null) {
            return [
                $errorFeedbackItemResolution,
            ];
        }
        return [];
    }
    /**
     * @throws AbstractException In case of error
     */
    public function executeMutation(FieldDataAccessorInterface $fieldDataAccessor): mixed
    {
        $user_id = App::getState('current-user-id');

        $this->getUserStateTypeMutationAPI()->logout();

        // Modify the routing-state with the newly logged in user info
        AppStateHelpers::resetCurrentUserInAppState();

        App::doAction('gd:user:loggedout', $user_id);
        return $user_id;
    }
}
