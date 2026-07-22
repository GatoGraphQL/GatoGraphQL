<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\MutationResolvers;

use PoPCMSSchema\UserMutations\Constants\MutationInputProperties;
use PoPCMSSchema\UserMutations\Constants\UserCRUDHookNames;
use PoPCMSSchema\UserMutations\Exception\UserCRUDMutationException;
use PoPCMSSchema\UserMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\UserMutations\TypeAPIs\UserTypeMutationAPIInterface;
use PoPCMSSchema\Users\TypeAPIs\UserTypeAPIInterface;
use PoPCMSSchema\UserStateMutations\MutationResolvers\ValidateUserLoggedInMutationResolverTrait;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\App;

abstract class AbstractDeleteUserMutationResolver extends AbstractMutationResolver
{
    use DeleteUserMutationResolverTrait;
    use ValidateUserLoggedInMutationResolverTrait;
    use UserMutationResolverTrait;

    private ?UserTypeAPIInterface $userTypeAPI = null;
    private ?UserTypeMutationAPIInterface $userTypeMutationAPI = null;

    final protected function getUserTypeAPI(): UserTypeAPIInterface
    {
        if ($this->userTypeAPI === null) {
            /** @var UserTypeAPIInterface */
            $userTypeAPI = $this->instanceManager->getInstance(UserTypeAPIInterface::class);
            $this->userTypeAPI = $userTypeAPI;
        }
        return $this->userTypeAPI;
    }

    final protected function getUserTypeMutationAPI(): UserTypeMutationAPIInterface
    {
        if ($this->userTypeMutationAPI === null) {
            /** @var UserTypeMutationAPIInterface */
            $userTypeMutationAPI = $this->instanceManager->getInstance(UserTypeMutationAPIInterface::class);
            $this->userTypeMutationAPI = $userTypeMutationAPI;
        }
        return $this->userTypeMutationAPI;
    }

    protected function getUserNotLoggedInError(): FeedbackItemResolution
    {
        return new FeedbackItemResolution(
            MutationErrorFeedbackItemProvider::class,
            MutationErrorFeedbackItemProvider::E5,
        );
    }

    protected function validateDeleteErrors(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $errorCount = $objectTypeFieldResolutionFeedbackStore->getErrorCount();

        $userID = $this->getUserID($fieldDataAccessor);

        $this->validateIsUserLoggedIn($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return;
        }

        // Type-level capability, checked before existence so as not to leak it
        $this->validateCanLoggedInUserDeleteUsers($userID, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return;
        }

        $this->validateUserByIDExists($userID, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return;
        }

        /** @var string|int */
        $userID = $userID;

        $this->validateUserIsNotDeletingThemselves($userID, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return;
        }

        $this->validateCanLoggedInUserDeleteUser($userID, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return;
        }

        $this->validateReassignUserExists($userID, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return;
        }

        // Allow components to inject their own validations
        App::doAction(
            UserCRUDHookNames::VALIDATE_DELETE_USER,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }

    protected function validateCanLoggedInUserDeleteUsers(
        string|int|null $userID,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        if ($this->getUserTypeMutationAPI()->canLoggedInUserDeleteUsers()) {
            return;
        }
        $objectTypeFieldResolutionFeedbackStore->addError(
            new ObjectTypeFieldResolutionFeedback(
                new FeedbackItemResolution(
                    MutationErrorFeedbackItemProvider::class,
                    MutationErrorFeedbackItemProvider::E8,
                    [
                        $userID ?? '',
                    ]
                ),
                $fieldDataAccessor->getField(),
            )
        );
    }

    protected function validateUserIsNotDeletingThemselves(
        string|int $userID,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $loggedInUserID = App::getState('current-user-id');
        if ((int) $loggedInUserID !== (int) $userID) {
            return;
        }
        $objectTypeFieldResolutionFeedbackStore->addError(
            new ObjectTypeFieldResolutionFeedback(
                new FeedbackItemResolution(
                    MutationErrorFeedbackItemProvider::class,
                    MutationErrorFeedbackItemProvider::E15,
                ),
                $fieldDataAccessor->getField(),
            )
        );
    }

    protected function validateCanLoggedInUserDeleteUser(
        string|int $userID,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        if ($this->getUserTypeMutationAPI()->canLoggedInUserDeleteUser($userID)) {
            return;
        }
        $objectTypeFieldResolutionFeedbackStore->addError(
            new ObjectTypeFieldResolutionFeedback(
                new FeedbackItemResolution(
                    MutationErrorFeedbackItemProvider::class,
                    MutationErrorFeedbackItemProvider::E8,
                    [
                        $userID,
                    ]
                ),
                $fieldDataAccessor->getField(),
            )
        );
    }

    protected function validateReassignUserExists(
        string|int $userID,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        if (!$fieldDataAccessor->hasValue(MutationInputProperties::REASSIGN_AUTHOR_CONTENT_TO)) {
            return;
        }
        /** @var string|int|null */
        $reassignUserID = $fieldDataAccessor->getValue(MutationInputProperties::REASSIGN_AUTHOR_CONTENT_TO);
        if ($reassignUserID === null) {
            return;
        }
        if (
            (int) $reassignUserID !== (int) $userID
            && $this->getUserTypeAPI()->getUserByID($reassignUserID) !== null
        ) {
            return;
        }
        $objectTypeFieldResolutionFeedbackStore->addError(
            new ObjectTypeFieldResolutionFeedback(
                new FeedbackItemResolution(
                    MutationErrorFeedbackItemProvider::class,
                    MutationErrorFeedbackItemProvider::E14,
                    [
                        $reassignUserID,
                    ]
                ),
                $fieldDataAccessor->getField(),
            )
        );
    }

    /**
     * @return bool Whether the user was deleted
     * @throws UserCRUDMutationException If there was an error (eg: User does not exist)
     */
    protected function delete(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): bool {
        /** @var string|int */
        $userID = $this->getUserID($fieldDataAccessor);

        /** @var string|int|null */
        $reassignUserID = $fieldDataAccessor->hasValue(MutationInputProperties::REASSIGN_AUTHOR_CONTENT_TO)
            ? $fieldDataAccessor->getValue(MutationInputProperties::REASSIGN_AUTHOR_CONTENT_TO)
            : null;

        $this->getUserTypeMutationAPI()->deleteUser($userID, $reassignUserID);

        App::doAction(
            UserCRUDHookNames::DELETE_USER,
            $userID,
            $fieldDataAccessor,
        );

        return true;
    }
}
