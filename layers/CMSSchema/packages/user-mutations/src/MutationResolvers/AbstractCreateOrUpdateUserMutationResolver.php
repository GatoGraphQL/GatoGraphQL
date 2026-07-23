<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\MutationResolvers;

use DateTime;
use PoPCMSSchema\UserMutations\Constants\MutationInputProperties;
use PoPCMSSchema\UserMutations\Constants\UserCRUDHookNames;
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

abstract class AbstractCreateOrUpdateUserMutationResolver extends AbstractMutationResolver
{
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

    abstract protected function addUserInputField(): bool;

    public function validate(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $errorCount = $objectTypeFieldResolutionFeedbackStore->getErrorCount();

        // Check that the user is logged-in
        $this->validateIsUserLoggedIn($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return;
        }

        // If updating, check that the user exists
        if ($this->addUserInputField()) {
            $userID = $this->getUserID($fieldDataAccessor);
            $this->validateUserByIDExists($userID, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
            if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
                return;
            }
        }

        // Check that the logged-in user has the capability to execute the mutation
        $this->validateUserCanExecuteMutation($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return;
        }

        $this->validateEmail($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        $this->validateRoles($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);

        // Allow components to inject their own validations
        App::doAction(
            UserCRUDHookNames::VALIDATE_CREATE_OR_UPDATE_USER,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }

    abstract protected function validateUserCanExecuteMutation(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void;

    protected function validateEmail(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        if (!$fieldDataAccessor->hasValue(MutationInputProperties::EMAIL)) {
            return;
        }
        /** @var string|null */
        $email = $fieldDataAccessor->getValue(MutationInputProperties::EMAIL);
        if ($email === null || $email === '') {
            return;
        }

        if (!$this->getUserTypeMutationAPI()->isValidEmail($email)) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        MutationErrorFeedbackItemProvider::class,
                        MutationErrorFeedbackItemProvider::E12,
                        [
                            $email,
                        ]
                    ),
                    $fieldDataAccessor->getField(),
                )
            );
            return;
        }

        $existingUser = $this->getUserTypeAPI()->getUserByEmail($email);
        if ($existingUser === null) {
            return;
        }
        $existingUserID = $this->getUserTypeAPI()->getUserID($existingUser);
        $currentUserID = $this->addUserInputField() ? $this->getUserID($fieldDataAccessor) : null;
        if ($currentUserID !== null && (int) $existingUserID === (int) $currentUserID) {
            return;
        }
        $objectTypeFieldResolutionFeedbackStore->addError(
            new ObjectTypeFieldResolutionFeedback(
                new FeedbackItemResolution(
                    MutationErrorFeedbackItemProvider::class,
                    MutationErrorFeedbackItemProvider::E11,
                    [
                        $email,
                    ]
                ),
                $fieldDataAccessor->getField(),
            )
        );
    }

    protected function validateRoles(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        if (!$fieldDataAccessor->hasValue(MutationInputProperties::ROLES)) {
            return;
        }
        /** @var string[]|null */
        $roles = $fieldDataAccessor->getValue(MutationInputProperties::ROLES);
        if ($roles === null || $roles === []) {
            return;
        }

        $errorCount = $objectTypeFieldResolutionFeedbackStore->getErrorCount();
        foreach ($roles as $role) {
            if ($this->getUserTypeMutationAPI()->roleExists($role)) {
                continue;
            }
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        MutationErrorFeedbackItemProvider::class,
                        MutationErrorFeedbackItemProvider::E13,
                        [
                            $role,
                        ]
                    ),
                    $fieldDataAccessor->getField(),
                )
            );
        }
        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return;
        }

        // Assigning roles requires the capability to promote users
        if ($this->getUserTypeMutationAPI()->canLoggedInUserPromoteUsers()) {
            return;
        }
        $objectTypeFieldResolutionFeedbackStore->addError(
            new ObjectTypeFieldResolutionFeedback(
                new FeedbackItemResolution(
                    MutationErrorFeedbackItemProvider::class,
                    MutationErrorFeedbackItemProvider::E9,
                ),
                $fieldDataAccessor->getField(),
            )
        );
    }

    protected function additionals(string|int $userID, FieldDataAccessorInterface $fieldDataAccessor): void
    {
    }

    protected function triggerExecuteCreateOrUpdateHook(
        string|int $userID,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        App::doAction(
            UserCRUDHookNames::CREATE_OR_UPDATE_USER,
            $userID,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }

    /**
     * @return array<string,mixed>
     */
    protected function getUserData(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        $userData = [];

        foreach (
            [
            MutationInputProperties::USERNAME => 'username',
            MutationInputProperties::EMAIL => 'email',
            MutationInputProperties::PASSWORD => 'password',
            MutationInputProperties::FIRST_NAME => 'firstName',
            MutationInputProperties::LAST_NAME => 'lastName',
            MutationInputProperties::NICKNAME => 'nickname',
            MutationInputProperties::DISPLAY_NAME => 'displayName',
            MutationInputProperties::SLUG => 'slug',
            MutationInputProperties::WEBSITE_URL => 'websiteURL',
            MutationInputProperties::DESCRIPTION => 'description',
            MutationInputProperties::LOCALE => 'locale',
            MutationInputProperties::ROLES => 'roles',
            MutationInputProperties::SEND_EMAIL_NOTIFICATION => 'sendEmailNotification',
            ] as $inputFieldName => $userDataKey
        ) {
            if (!$fieldDataAccessor->hasValue($inputFieldName)) {
                continue;
            }
            $userData[$userDataKey] = $fieldDataAccessor->getValue($inputFieldName);
        }

        if ($fieldDataAccessor->hasValue(MutationInputProperties::REGISTERED_DATE)) {
            /** @var DateTime|null */
            $registeredDate = $fieldDataAccessor->getValue(MutationInputProperties::REGISTERED_DATE);
            if ($registeredDate !== null) {
                $userData['registeredDate'] = $registeredDate->format('Y-m-d H:i:s');
            }
        }

        if ($this->addUserInputField()) {
            $userData['id'] = $this->getUserID($fieldDataAccessor);
        }

        return App::applyFilters(
            UserCRUDHookNames::GET_CREATE_OR_UPDATE_USER_DATA,
            $userData,
            $fieldDataAccessor,
        );
    }
}
