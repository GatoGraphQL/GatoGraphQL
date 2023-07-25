<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostUserMutations\Hooks;

use PoPCMSSchema\CustomPostMutations\Constants\HookNames;
use PoPCMSSchema\CustomPostUserMutations\Constants\MutationInputProperties;
use PoPCMSSchema\CustomPostUserMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\CustomPostUserMutations\ObjectModels\UserDoesNotExistErrorPayload;
use PoPCMSSchema\Users\TypeAPIs\UserTypeAPIInterface;
use PoPSchema\SchemaCommons\ObjectModels\ErrorPayloadInterface;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
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
        if (isset($authorBy->id)) {
            /** @var string|int */
            $authorID = $authorBy->id;
            $this->validateUserByIDExists(
                $authorID,
                $fieldDataAccessor,
                $objectTypeFieldResolutionFeedbackStore,
            );
        } elseif (isset($authorBy->username)) {
            /** @var string */
            $authorUsername = $authorBy->username;
            $this->validateUserByUsernameExists(
                $authorUsername,
                $fieldDataAccessor,
                $objectTypeFieldResolutionFeedbackStore,
            );
        } elseif (isset($authorBy->email)) {
            /** @var string */
            $authorEmail = $authorBy->email;
            $this->validateUserByEmailExists(
                $authorEmail,
                $fieldDataAccessor,
                $objectTypeFieldResolutionFeedbackStore,
            );
        }
    }

    /**
     * Entry "authorID" must either have an ID or `null` to execute
     * the mutation. Only if not provided, then nothing to do.
     */
    protected function hasProvidedAuthorInput(
        FieldDataAccessorInterface $fieldDataAccessor,
    ): bool {
        return $fieldDataAccessor->hasValue(MutationInputProperties::AUTHOR_BY);
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
        $userTypeAPI = $this->getUserTypeAPI();
        /** @var stdClass|null */
        $authorBy = $fieldDataAccessor->getValue(MutationInputProperties::AUTHOR_BY);
        if (isset($authorBy->id)) {
            /** @var string|int */
            $authorID = $authorBy->id;
        } elseif (isset($authorBy->username)) {
            /** @var string */
            $authorUsername = $authorBy->username;
            $user = $userTypeAPI->getUserByLogin($authorUsername);
            $authorID = $userTypeAPI->getUserID($user);
        } elseif (isset($authorBy->email)) {
            /** @var string */
            $authorEmail = $authorBy->email;
            $user = $userTypeAPI->getUserByEmail($authorEmail);
            $authorID = $userTypeAPI->getUserID($user);
        }
        $customPostData['author-id'] = $authorID;
        return $customPostData;
    }

    public function createErrorPayloadFromObjectTypeFieldResolutionFeedback(
        ErrorPayloadInterface $errorPayload,
        FeedbackItemResolution $feedbackItemResolution
    ): ErrorPayloadInterface {
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
