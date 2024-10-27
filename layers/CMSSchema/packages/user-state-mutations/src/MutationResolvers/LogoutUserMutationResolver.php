<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateMutations\MutationResolvers;

use PoPCMSSchema\UserStateMutations\Constants\HookNames;
use PoPCMSSchema\UserStateMutations\StaticHelpers\AppStateHelpers;
use PoPCMSSchema\UserStateMutations\TypeAPIs\UserStateTypeMutationAPIInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\App;

class LogoutUserMutationResolver extends AbstractMutationResolver
{
    use ValidateUserLoggedInMutationResolverTrait;

    private ?UserStateTypeMutationAPIInterface $userStateTypeMutationAPI = null;

    final protected function getUserStateTypeMutationAPI(): UserStateTypeMutationAPIInterface
    {
        if ($this->userStateTypeMutationAPI === null) {
            /** @var UserStateTypeMutationAPIInterface */
            $userStateTypeMutationAPI = $this->instanceManager->getInstance(UserStateTypeMutationAPIInterface::class);
            $this->userStateTypeMutationAPI = $userStateTypeMutationAPI;
        }
        return $this->userStateTypeMutationAPI;
    }

    public function validate(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $errorFeedbackItemResolution = $this->validateUserIsLoggedIn();
        if ($errorFeedbackItemResolution !== null) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    $errorFeedbackItemResolution,
                    $fieldDataAccessor->getField(),
                )
            );
        }
    }

    public function executeMutation(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $user_id = App::getState('current-user-id');

        $this->getUserStateTypeMutationAPI()->logout();

        // Modify the routing-state with the newly logged in user info
        AppStateHelpers::resetCurrentUserInAppState();

        App::doAction(HookNames::USER_LOGGED_OUT, $user_id);
        return $user_id;
    }
}
