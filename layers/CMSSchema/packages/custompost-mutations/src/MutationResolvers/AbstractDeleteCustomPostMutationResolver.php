<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\MutationResolvers;

use PoPCMSSchema\CustomPostMutations\Constants\MutationInputProperties;
use PoPCMSSchema\CustomPostMutations\Exception\CustomPostCRUDMutationException;
use PoPCMSSchema\CustomPostMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\CustomPostMutations\TypeAPIs\CustomPostTypeMutationAPIInterface;
use PoPCMSSchema\CustomPosts\Enums\CustomPostStatus;
use PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoPCMSSchema\UserStateMutations\MutationResolvers\ValidateUserLoggedInMutationResolverTrait;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\App;

abstract class AbstractDeleteCustomPostMutationResolver extends AbstractMutationResolver implements CustomPostMutationResolverInterface
{
    use DeleteCustomPostMutationResolverTrait;
    use ValidateUserLoggedInMutationResolverTrait;

    private ?CustomPostTypeAPIInterface $customPostTypeAPI = null;
    private ?CustomPostTypeMutationAPIInterface $customPostTypeMutationAPI = null;

    final protected function getCustomPostTypeAPI(): CustomPostTypeAPIInterface
    {
        if ($this->customPostTypeAPI === null) {
            /** @var CustomPostTypeAPIInterface */
            $customPostTypeAPI = $this->instanceManager->getInstance(CustomPostTypeAPIInterface::class);
            $this->customPostTypeAPI = $customPostTypeAPI;
        }
        return $this->customPostTypeAPI;
    }

    final protected function getCustomPostTypeMutationAPI(): CustomPostTypeMutationAPIInterface
    {
        if ($this->customPostTypeMutationAPI === null) {
            /** @var CustomPostTypeMutationAPIInterface */
            $customPostTypeMutationAPI = $this->instanceManager->getInstance(CustomPostTypeMutationAPIInterface::class);
            $this->customPostTypeMutationAPI = $customPostTypeMutationAPI;
        }
        return $this->customPostTypeMutationAPI;
    }

    protected function getUserNotLoggedInError(): FeedbackItemResolution
    {
        return new FeedbackItemResolution(
            MutationErrorFeedbackItemProvider::class,
            MutationErrorFeedbackItemProvider::E13,
        );
    }

    protected function validateDeleteErrors(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $errorCount = $objectTypeFieldResolutionFeedbackStore->getErrorCount();

        /** @var string|int|null */
        $customPostID = $fieldDataAccessor->getValue(MutationInputProperties::ID);

        $this->validateIsUserLoggedIn($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return;
        }

        $this->validateCustomPostExists($customPostID, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return;
        }

        /** @var string|int */
        $customPostID = $customPostID;

        $targetCustomPostType = $this->getTargetCustomPostType();
        if ($targetCustomPostType !== null) {
            $this->validateIsCustomPostType($customPostID, $targetCustomPostType, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
            if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
                return;
            }
        }

        $this->validateCanLoggedInUserDeleteCustomPost($customPostID, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return;
        }

        $this->validateCanCustomPostBeTrashed($customPostID, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }

    /**
     * The custom post type that the mutation can delete, or `null` when
     * it can delete a custom post of any type.
     */
    protected function getTargetCustomPostType(): ?string
    {
        return null;
    }

    /**
     * Validate that the custom post is of the type handled by the mutation,
     * so that (eg) `deletePost` cannot delete a page.
     */
    protected function validateIsCustomPostType(
        string|int $customPostID,
        string $customPostType,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        if ($this->getCustomPostTypeAPI()->getCustomPostType($customPostID) === $customPostType) {
            return;
        }
        $objectTypeFieldResolutionFeedbackStore->addError(
            new ObjectTypeFieldResolutionFeedback(
                new FeedbackItemResolution(
                    MutationErrorFeedbackItemProvider::class,
                    MutationErrorFeedbackItemProvider::E5,
                    [
                        $customPostID,
                        $customPostType,
                    ]
                ),
                $fieldDataAccessor->getField(),
            )
        );
    }

    /**
     * Validate that the custom post exists. The ID is mandatory in the
     * input, so a missing ID should never happen.
     */
    protected function validateCustomPostExists(
        string|int|null $customPostID,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        if (!$customPostID) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        MutationErrorFeedbackItemProvider::class,
                        MutationErrorFeedbackItemProvider::E6,
                    ),
                    $fieldDataAccessor->getField(),
                )
            );
            return;
        }

        if (!$this->getCustomPostTypeAPI()->customPostExists($customPostID)) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        MutationErrorFeedbackItemProvider::class,
                        MutationErrorFeedbackItemProvider::E7,
                        [
                            $customPostID,
                        ]
                    ),
                    $fieldDataAccessor->getField(),
                )
            );
        }
    }

    /**
     * Check that the logged-in user can delete this specific custom post.
     *
     * The `delete_post` meta capability is resolved by the CMS, which takes
     * into account the ownership of the custom post and its status, hence
     * this single check also covers "delete others'" and "delete published".
     */
    protected function validateCanLoggedInUserDeleteCustomPost(
        string|int $customPostID,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $userID = App::getState('current-user-id');
        if ($this->getCustomPostTypeMutationAPI()->canUserDeleteCustomPost($userID, $customPostID)) {
            return;
        }
        $objectTypeFieldResolutionFeedbackStore->addError(
            new ObjectTypeFieldResolutionFeedback(
                new FeedbackItemResolution(
                    MutationErrorFeedbackItemProvider::class,
                    MutationErrorFeedbackItemProvider::E14,
                    [
                        $customPostID,
                    ]
                ),
                $fieldDataAccessor->getField(),
            )
        );
    }

    /**
     * When not deleting permanently, the custom post must support being
     * sent to the trash, and must not be in the trash already.
     */
    protected function validateCanCustomPostBeTrashed(
        string|int $customPostID,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        if ($this->isForceDelete($fieldDataAccessor)) {
            return;
        }

        /** @var string */
        $customPostType = $this->getCustomPostTypeAPI()->getCustomPostType($customPostID);
        if (!$this->getCustomPostTypeMutationAPI()->doesCustomPostTypeSupportTrash($customPostType)) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        MutationErrorFeedbackItemProvider::class,
                        MutationErrorFeedbackItemProvider::E16,
                        [
                            $customPostID,
                        ]
                    ),
                    $fieldDataAccessor->getField(),
                )
            );
            return;
        }

        if ($this->getCustomPostTypeAPI()->getStatus($customPostID) === CustomPostStatus::TRASH) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        MutationErrorFeedbackItemProvider::class,
                        MutationErrorFeedbackItemProvider::E17,
                        [
                            $customPostID,
                        ]
                    ),
                    $fieldDataAccessor->getField(),
                )
            );
        }
    }

    protected function isForceDelete(FieldDataAccessorInterface $fieldDataAccessor): bool
    {
        return $fieldDataAccessor->getValue(MutationInputProperties::FORCE) === true;
    }

    /**
     * @return string|int The ID of the deleted entity
     * @throws CustomPostCRUDMutationException If there was an error (eg: Custom Post does not exist)
     */
    protected function delete(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): string|int {
        /** @var string|int */
        $customPostID = $fieldDataAccessor->getValue(MutationInputProperties::ID);

        if ($this->isForceDelete($fieldDataAccessor)) {
            $this->getCustomPostTypeMutationAPI()->deleteCustomPost($customPostID);
        } else {
            $this->getCustomPostTypeMutationAPI()->trashCustomPost($customPostID);
        }

        return $customPostID;
    }
}
