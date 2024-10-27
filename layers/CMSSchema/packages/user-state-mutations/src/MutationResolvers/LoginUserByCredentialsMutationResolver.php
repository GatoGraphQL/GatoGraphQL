<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateMutations\MutationResolvers;

use PoPCMSSchema\UserStateMutations\Constants\MutationInputProperties;
use PoPCMSSchema\UserStateMutations\Exception\UserStateMutationException;
use PoPCMSSchema\UserStateMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\UserStateMutations\StaticHelpers\AppStateHelpers;
use PoPCMSSchema\UserStateMutations\TypeAPIs\UserStateTypeMutationAPIInterface;
use PoPCMSSchema\Users\TypeAPIs\UserTypeAPIInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\App;
use PoP\Root\Exception\AbstractException;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoPCMSSchema\UserStateMutations\Constants\HookNames;

class LoginUserByCredentialsMutationResolver extends AbstractMutationResolver
{
    private ?UserTypeAPIInterface $userTypeAPI = null;
    private ?UserStateTypeMutationAPIInterface $userStateTypeMutationAPI = null;

    final protected function getUserTypeAPI(): UserTypeAPIInterface
    {
        if ($this->userTypeAPI === null) {
            /** @var UserTypeAPIInterface */
            $userTypeAPI = $this->instanceManager->getInstance(UserTypeAPIInterface::class);
            $this->userTypeAPI = $userTypeAPI;
        }
        return $this->userTypeAPI;
    }
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
        $usernameOrEmail = $fieldDataAccessor->getValue(MutationInputProperties::USERNAME_OR_EMAIL);
        $pwd = $fieldDataAccessor->getValue(MutationInputProperties::PASSWORD);

        if (!$usernameOrEmail) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        MutationErrorFeedbackItemProvider::class,
                        MutationErrorFeedbackItemProvider::E2,
                    ),
                    $fieldDataAccessor->getField(),
                )
            );
        }
        if (!$pwd) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        MutationErrorFeedbackItemProvider::class,
                        MutationErrorFeedbackItemProvider::E3,
                    ),
                    $fieldDataAccessor->getField(),
                )
            );
        }

        if (App::getState('is-user-logged-in')) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    $this->getUserAlreadyLoggedInError(App::getState('current-user-id')),
                    $fieldDataAccessor->getField(),
                )
            );
        }
    }

    protected function getUserAlreadyLoggedInError(string|int $user_id): FeedbackItemResolution
    {
        return new FeedbackItemResolution(
            MutationErrorFeedbackItemProvider::class,
            MutationErrorFeedbackItemProvider::E4,
        );
    }

    /**
     * @throws AbstractException In case of error
     */
    public function executeMutation(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        // If the user is already logged in, then return the error
        $usernameOrEmail = $fieldDataAccessor->getValue(MutationInputProperties::USERNAME_OR_EMAIL);
        $pwd = $fieldDataAccessor->getValue(MutationInputProperties::PASSWORD);

        // Find out if it was a username or an email that was provided
        $isEmail = str_contains($usernameOrEmail, '@');
        if ($isEmail) {
            $email = $usernameOrEmail;
            $user = $this->getUserTypeAPI()->getUserByEmail($email);
            if ($user === null) {
                $objectTypeFieldResolutionFeedbackStore->addError(
                    new ObjectTypeFieldResolutionFeedback(
                        new FeedbackItemResolution(
                            MutationErrorFeedbackItemProvider::class,
                            MutationErrorFeedbackItemProvider::E6,
                            [
                                $email,
                            ]
                        ),
                        $fieldDataAccessor->getField(),
                    )
                );
                return null;
            }
            $username = $this->getUserTypeAPI()->getUserLogin($user);
        } else {
            $username = $usernameOrEmail;
        }

        $credentials = array(
            'login' => $username,
            'password' => $pwd,
            'remember' => true,
        );
        try {
            $user = $this->getUserStateTypeMutationAPI()->login($credentials);

            // Modify the routing-state with the newly logged in user info
            AppStateHelpers::resetCurrentUserInAppState();

            $userID = $this->getUserTypeAPI()->getUserID($user);
            App::doAction(HookNames::USER_LOGGED_IN, $userID);
            return $userID;
        } catch (UserStateMutationException $userStateMutationException) {
            $this->transferErrorFromUserStateMutationExceptionToFieldResolutionFeedbackStore(
                $userStateMutationException,
                $fieldDataAccessor,
                $objectTypeFieldResolutionFeedbackStore,
            );
            return null;
        }
    }

    protected function transferErrorFromUserStateMutationExceptionToFieldResolutionFeedbackStore(
        UserStateMutationException $userStateMutationException,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $userLogin = '';
        if ($errorData = $userStateMutationException->getData()) {
            $userLogin = $errorData->userLogin ?? '';
        }
        $errorFieldResolutionFeedback = match ($userStateMutationException->getErrorCode()) {
            'invalid_username' => new FeedbackItemResolution(
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E5,
                [
                    $userLogin,
                ]
            ),
            'invalid_email' => new FeedbackItemResolution(
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E6,
                [
                    $userLogin,
                ]
            ),
            'incorrect_password' => new FeedbackItemResolution(
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E7,
            ),
            default => null,
        };
        $objectTypeFieldResolutionFeedbackStore->addError(
            new ObjectTypeFieldResolutionFeedback(
                $errorFieldResolutionFeedback !== null
                    ? $errorFieldResolutionFeedback
                    : new FeedbackItemResolution(
                        MutationErrorFeedbackItemProvider::class,
                        MutationErrorFeedbackItemProvider::E8,
                        [
                            $userStateMutationException->getErrorCode() ?? 'undefined error code',
                            $userStateMutationException->getMessage(),
                        ]
                    ),
                $fieldDataAccessor->getField(),
            )
        );
    }
}
