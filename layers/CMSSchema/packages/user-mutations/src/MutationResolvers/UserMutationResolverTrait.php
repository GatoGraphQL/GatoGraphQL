<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\MutationResolvers;

use PoPCMSSchema\UserMutations\Constants\MutationInputProperties;
use PoPCMSSchema\UserMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\UserMutations\ObjectModels\EmailAlreadyExistsErrorPayload;
use PoPCMSSchema\UserMutations\ObjectModels\InvalidEmailErrorPayload;
use PoPCMSSchema\UserMutations\ObjectModels\LoggedInUserHasNoPermissionToCreateUsersErrorPayload;
use PoPCMSSchema\UserMutations\ObjectModels\LoggedInUserHasNoPermissionToEditUserErrorPayload;
use PoPCMSSchema\UserMutations\ObjectModels\LoggedInUserHasNoPermissionToEditUserRolesErrorPayload;
use PoPCMSSchema\UserMutations\ObjectModels\UserDoesNotExistErrorPayload;
use PoPCMSSchema\UserMutations\ObjectModels\UserRoleDoesNotExistErrorPayload;
use PoPCMSSchema\UserMutations\ObjectModels\UsernameAlreadyExistsErrorPayload;
use PoPCMSSchema\UserMutations\TypeAPIs\UserTypeMutationAPIInterface;
use PoPCMSSchema\Users\TypeAPIs\UserTypeAPIInterface;
use PoPCMSSchema\UserStateMutations\ObjectModels\UserIsNotLoggedInErrorPayload;
use PoPSchema\SchemaCommons\ObjectModels\ErrorPayloadInterface;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;

trait UserMutationResolverTrait
{
    abstract protected function getUserTypeAPI(): UserTypeAPIInterface;

    abstract protected function getUserTypeMutationAPI(): UserTypeMutationAPIInterface;

    /**
     * Validate that the user exists.
     */
    protected function validateUserByIDExists(
        string|int|null $userID,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        if (!$userID || $this->getUserTypeAPI()->getUserByID($userID) === null) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        MutationErrorFeedbackItemProvider::class,
                        MutationErrorFeedbackItemProvider::E1,
                        [
                            $userID ?? '',
                        ]
                    ),
                    $fieldDataAccessor->getField(),
                )
            );
        }
    }

    protected function createCreateOrUpdateUserErrorPayloadFromObjectTypeFieldResolutionFeedback(
        ObjectTypeFieldResolutionFeedbackInterface $objectTypeFieldResolutionFeedback,
    ): ?ErrorPayloadInterface {
        $feedbackItemResolution = $objectTypeFieldResolutionFeedback->getFeedbackItemResolution();
        return match (
            [
            $feedbackItemResolution->getFeedbackProviderServiceClass(),
            $feedbackItemResolution->getCode()
            ]
        ) {
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E5,
            ] => new UserIsNotLoggedInErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E7,
            ] => new LoggedInUserHasNoPermissionToCreateUsersErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E1,
            ] => new UserDoesNotExistErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E4,
            ] => new LoggedInUserHasNoPermissionToEditUserErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E9,
            ] => new LoggedInUserHasNoPermissionToEditUserRolesErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E10,
            ] => new UsernameAlreadyExistsErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E11,
            ] => new EmailAlreadyExistsErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E12,
            ] => new InvalidEmailErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E13,
            ] => new UserRoleDoesNotExistErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            default => null,
        };
    }

    protected function getUserID(FieldDataAccessorInterface $fieldDataAccessor): string|int|null
    {
        /** @var string|int|null */
        return $fieldDataAccessor->getValue(MutationInputProperties::ID);
    }
}
