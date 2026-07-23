<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\MutationResolvers;

use PoPCMSSchema\UserMutations\Constants\MutationInputProperties;
use PoPCMSSchema\UserMutations\Constants\UserCRUDHookNames;
use PoPCMSSchema\UserMutations\Exception\UserCRUDMutationException;
use PoPCMSSchema\UserMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\App;
use PoP\Root\Exception\AbstractException;

class CreateUserMutationResolver extends AbstractCreateOrUpdateUserMutationResolver
{
    protected function addUserInputField(): bool
    {
        return false;
    }

    protected function validateUserCanExecuteMutation(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        if ($this->getUserTypeMutationAPI()->canLoggedInUserCreateUsers()) {
            return;
        }
        $objectTypeFieldResolutionFeedbackStore->addError(
            new ObjectTypeFieldResolutionFeedback(
                new FeedbackItemResolution(
                    MutationErrorFeedbackItemProvider::class,
                    MutationErrorFeedbackItemProvider::E7,
                ),
                $fieldDataAccessor->getField(),
            )
        );
    }

    public function validate(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $errorCount = $objectTypeFieldResolutionFeedbackStore->getErrorCount();

        parent::validate($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return;
        }

        $this->validateUsernameDoesNotExist($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);

        // Allow components to inject their own validations
        App::doAction(
            UserCRUDHookNames::VALIDATE_CREATE_USER,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }

    protected function validateUsernameDoesNotExist(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        /** @var string|null */
        $username = $fieldDataAccessor->getValue(MutationInputProperties::USERNAME);
        if ($username === null || $username === '') {
            return;
        }
        if ($this->getUserTypeAPI()->getUserByLogin($username) === null) {
            return;
        }
        $objectTypeFieldResolutionFeedbackStore->addError(
            new ObjectTypeFieldResolutionFeedback(
                new FeedbackItemResolution(
                    MutationErrorFeedbackItemProvider::class,
                    MutationErrorFeedbackItemProvider::E10,
                    [
                        $username,
                    ]
                ),
                $fieldDataAccessor->getField(),
            )
        );
    }

    protected function additionals(string|int $userID, FieldDataAccessorInterface $fieldDataAccessor): void
    {
        parent::additionals($userID, $fieldDataAccessor);
        App::doAction(UserCRUDHookNames::CREATE_USER, $userID, $fieldDataAccessor);
    }

    /**
     * @throws AbstractException In case of error
     */
    public function executeMutation(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $userData = $this->getUserData($fieldDataAccessor);

        $userID = $this->getUserTypeMutationAPI()->createUser($userData);

        // Allow for additional operations
        $this->additionals($userID, $fieldDataAccessor);
        $this->triggerExecuteCreateOrUpdateHook($userID, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);

        return $userID;
    }
}
