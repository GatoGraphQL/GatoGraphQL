<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostUserMutations\Hooks;

use PoPCMSSchema\CustomPostMutations\Constants\HookNames;
use PoPCMSSchema\CustomPostUserMutations\Constants\MutationInputProperties;
use PoPCMSSchema\CustomPostUserMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\CustomPostUserMutations\ObjectModels\UserDoesNotExistErrorPayload;
use PoPCMSSchema\Users\Constants\InputProperties;
use PoPCMSSchema\Users\TypeAPIs\UserTypeAPIInterface;
use PoPSchema\SchemaCommons\ObjectModels\ErrorPayloadInterface;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use stdClass;

class MutationResolverHookSet extends AbstractHookSet
{
    private ?UserTypeAPIInterface $userTypeAPI = null;

    final public function setUserTypeAPI(UserTypeAPIInterface $userTypeAPI): void
    {
        $this->userTypeAPI = $userTypeAPI;
    }
    final protected function getUserTypeAPI(): UserTypeAPIInterface
    {
        if ($this->userTypeAPI === null) {
            /** @var UserTypeAPIInterface */
            $userTypeAPI = $this->instanceManager->getInstance(UserTypeAPIInterface::class);
            $this->userTypeAPI = $userTypeAPI;
        }
        return $this->userTypeAPI;
    }

    protected function init(): void
    {
        App::addAction(
            HookNames::VALIDATE_CREATE_OR_UPDATE,
            $this->maybeValidateAuthor(...),
            10,
            2
        );
        App::addFilter(
            HookNames::GET_CREATE_OR_UPDATE_DATA,
            $this->addCreateOrUpdateCustomPostData(...),
            10,
            2
        );
        App::addFilter(
            HookNames::ERROR_PAYLOAD,
            $this->createErrorPayloadFromObjectTypeFieldResolutionFeedback(...),
            10,
            2
        );
    }

    public function maybeValidateAuthor(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        if (!$this->hasProvidedAuthorInput($fieldDataAccessor)) {
            return;
        }
        /** @var stdClass|null */
        $authorBy = $fieldDataAccessor->getValue(MutationInputProperties::AUTHOR_BY);
        if ($authorBy === null) {
            return;
        }
        if (isset($authorBy->{InputProperties::ID})) {
            /** @var string|int */
            $authorID = $authorBy->{InputProperties::ID};
            $this->validateUserByIDExists(
                $authorID,
                $fieldDataAccessor,
                $objectTypeFieldResolutionFeedbackStore,
            );
        } elseif (isset($authorBy->{InputProperties::USERNAME})) {
            /** @var string */
            $authorUsername = $authorBy->{InputProperties::USERNAME};
            $this->validateUserByUsernameExists(
                $authorUsername,
                $fieldDataAccessor,
                $objectTypeFieldResolutionFeedbackStore,
            );
        } elseif (isset($authorBy->{InputProperties::EMAIL})) {
            /** @var string */
            $authorEmail = $authorBy->{InputProperties::EMAIL};
            $this->validateUserByEmailExists(
                $authorEmail,
                $fieldDataAccessor,
                $objectTypeFieldResolutionFeedbackStore,
            );
        }
    }

    protected function hasProvidedAuthorInput(
        FieldDataAccessorInterface $fieldDataAccessor,
    ): bool {
        if (!$fieldDataAccessor->hasValue(MutationInputProperties::AUTHOR_BY)) {
            return false;
        }

        /** @var stdClass|null */
        $authorBy = $fieldDataAccessor->getValue(MutationInputProperties::AUTHOR_BY);
        return isset($authorBy->{InputProperties::ID})
            || isset($authorBy->{InputProperties::USERNAME})
            || isset($authorBy->{InputProperties::EMAIL});
    }

    /**
     * @param array<string,mixed> $customPostData
     * @return array<string,mixed>
     */
    public function addCreateOrUpdateCustomPostData(
        array $customPostData,
        FieldDataAccessorInterface $fieldDataAccessor,
    ): array {
        if (!$this->hasProvidedAuthorInput($fieldDataAccessor)) {
            return $customPostData;
        }
        $authorID = null;
        $userTypeAPI = $this->getUserTypeAPI();
        /** @var stdClass|null */
        $authorBy = $fieldDataAccessor->getValue(MutationInputProperties::AUTHOR_BY);
        if (isset($authorBy->{InputProperties::ID})) {
            /** @var string|int */
            $authorID = $authorBy->{InputProperties::ID};
        } elseif (isset($authorBy->{InputProperties::USERNAME})) {
            /** @var string */
            $authorUsername = $authorBy->{InputProperties::USERNAME};
            /** @var object */
            $user = $userTypeAPI->getUserByLogin($authorUsername);
            $authorID = $userTypeAPI->getUserID($user);
        } elseif (isset($authorBy->{InputProperties::EMAIL})) {
            /** @var string */
            $authorEmail = $authorBy->{InputProperties::EMAIL};
            /** @var object */
            $user = $userTypeAPI->getUserByEmail($authorEmail);
            $authorID = $userTypeAPI->getUserID($user);
        }
        /** @var string|int $authorID */
        $customPostData['author-id'] = $authorID;
        return $customPostData;
    }

    public function createErrorPayloadFromObjectTypeFieldResolutionFeedback(
        ErrorPayloadInterface $errorPayload,
        ObjectTypeFieldResolutionFeedbackInterface $objectTypeFieldResolutionFeedback,
    ): ErrorPayloadInterface {
        $feedbackItemResolution = $objectTypeFieldResolutionFeedback->getFeedbackItemResolution();
        return match (
            [
            $feedbackItemResolution->getFeedbackProviderServiceClass(),
            $feedbackItemResolution->getCode()
            ]
        ) {
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E1,
            ] => new UserDoesNotExistErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E2,
            ] => new UserDoesNotExistErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E3,
            ] => new UserDoesNotExistErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            default => $errorPayload,
        };
    }

    protected function validateUserByIDExists(
        string|int $userID,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        if ($this->getUserTypeAPI()->getUserByID($userID) === null) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        MutationErrorFeedbackItemProvider::class,
                        MutationErrorFeedbackItemProvider::E1,
                        [
                            $userID,
                        ]
                    ),
                    $fieldDataAccessor->getField(),
                )
            );
        }
    }

    protected function validateUserByUsernameExists(
        string $username,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        if ($this->getUserTypeAPI()->getUserByLogin($username) === null) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        MutationErrorFeedbackItemProvider::class,
                        MutationErrorFeedbackItemProvider::E2,
                        [
                            $username,
                        ]
                    ),
                    $fieldDataAccessor->getField(),
                )
            );
        }
    }

    protected function validateUserByEmailExists(
        string $userEmail,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        if ($this->getUserTypeAPI()->getUserByEmail($userEmail) === null) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        MutationErrorFeedbackItemProvider::class,
                        MutationErrorFeedbackItemProvider::E3,
                        [
                            $userEmail,
                        ]
                    ),
                    $fieldDataAccessor->getField(),
                )
            );
        }
    }
}
