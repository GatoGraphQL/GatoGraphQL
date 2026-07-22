<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\MutationResolvers;

use PoPCMSSchema\UserMutations\Constants\UserCRUDHookNames;
use PoPCMSSchema\UserMutations\Exception\UserCRUDMutationException;
use PoPCMSSchema\UserMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\App;
use PoP\Root\Exception\AbstractException;

class UpdateUserMutationResolver extends AbstractCreateOrUpdateUserMutationResolver
{
    protected function addUserInputField(): bool
    {
        return true;
    }

    protected function validateUserCanExecuteMutation(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        /** @var string|int */
        $userID = $this->getUserID($fieldDataAccessor);
        if ($this->getUserTypeMutationAPI()->canLoggedInUserEditUser($userID)) {
            return;
        }
        $objectTypeFieldResolutionFeedbackStore->addError(
            new ObjectTypeFieldResolutionFeedback(
                new FeedbackItemResolution(
                    MutationErrorFeedbackItemProvider::class,
                    MutationErrorFeedbackItemProvider::E4,
                    [
                        $userID,
                    ]
                ),
                $fieldDataAccessor->getField(),
            )
        );
    }

    public function validate(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        parent::validate($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);

        // Allow components to inject their own validations
        App::doAction(
            UserCRUDHookNames::VALIDATE_UPDATE_USER,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }

    protected function additionals(string|int $userID, FieldDataAccessorInterface $fieldDataAccessor): void
    {
        parent::additionals($userID, $fieldDataAccessor);
        App::doAction(UserCRUDHookNames::UPDATE_USER, $userID, $fieldDataAccessor);
    }

    /**
     * @throws AbstractException In case of error
     */
    public function executeMutation(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $userData = $this->getUserData($fieldDataAccessor);

        $userID = $this->getUserTypeMutationAPI()->updateUser($userData);

        // Allow for additional operations
        $this->additionals($userID, $fieldDataAccessor);

        return $userID;
    }
}
