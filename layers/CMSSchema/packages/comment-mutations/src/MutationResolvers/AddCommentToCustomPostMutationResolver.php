<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\MutationResolvers;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\App;
use PoP\Root\Exception\AbstractException;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoPCMSSchema\CommentMutations\Exception\CommentCRUDMutationException;
use PoPCMSSchema\CommentMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\CommentMutations\Module;
use PoPCMSSchema\CommentMutations\ModuleConfiguration;
use PoPCMSSchema\CommentMutations\TypeAPIs\CommentTypeMutationAPIInterface;
use PoPCMSSchema\Comments\TypeAPIs\CommentTypeAPIInterface;
use PoPCMSSchema\Users\TypeAPIs\UserTypeAPIInterface;
use PoPCMSSchema\UserStateMutations\MutationResolvers\ValidateUserLoggedInMutationResolverTrait;

/**
 * Add a comment to a custom post. The user may be logged-in or not
 */
class AddCommentToCustomPostMutationResolver extends AbstractMutationResolver
{
    use ValidateUserLoggedInMutationResolverTrait;

    private ?CommentTypeAPIInterface $commentTypeAPI = null;
    private ?CommentTypeMutationAPIInterface $commentTypeMutationAPI = null;
    private ?UserTypeAPIInterface $userTypeAPI = null;

    final public function setCommentTypeAPI(CommentTypeAPIInterface $commentTypeAPI): void
    {
        $this->commentTypeAPI = $commentTypeAPI;
    }
    final protected function getCommentTypeAPI(): CommentTypeAPIInterface
    {
        /** @var CommentTypeAPIInterface */
        return $this->commentTypeAPI ??= $this->instanceManager->getInstance(CommentTypeAPIInterface::class);
    }
    final public function setCommentTypeMutationAPI(CommentTypeMutationAPIInterface $commentTypeMutationAPI): void
    {
        $this->commentTypeMutationAPI = $commentTypeMutationAPI;
    }
    final protected function getCommentTypeMutationAPI(): CommentTypeMutationAPIInterface
    {
        /** @var CommentTypeMutationAPIInterface */
        return $this->commentTypeMutationAPI ??= $this->instanceManager->getInstance(CommentTypeMutationAPIInterface::class);
    }
    final public function setUserTypeAPI(UserTypeAPIInterface $userTypeAPI): void
    {
        $this->userTypeAPI = $userTypeAPI;
    }
    final protected function getUserTypeAPI(): UserTypeAPIInterface
    {
        /** @var UserTypeAPIInterface */
        return $this->userTypeAPI ??= $this->instanceManager->getInstance(UserTypeAPIInterface::class);
    }

    public function validateErrors(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $field = $fieldDataAccessor->getField();

        // Check that the user is logged-in
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($moduleConfiguration->mustUserBeLoggedInToAddComment()) {
            $errorFeedbackItemResolution = $this->validateUserIsLoggedIn();
            if ($errorFeedbackItemResolution !== null) {
                $objectTypeFieldResolutionFeedbackStore->addError(
                    new ObjectTypeFieldResolutionFeedback(
                        $errorFeedbackItemResolution,
                        $field,
                    )
                );
                return;
            }
        } elseif ($moduleConfiguration->requireCommenterNameAndEmail()) {
            // Validate if the commenter's name and email are mandatory
            if (!$fieldDataAccessor->getValue(MutationInputProperties::AUTHOR_NAME)) {
                $objectTypeFieldResolutionFeedbackStore->addError(
                    new ObjectTypeFieldResolutionFeedback(
                        new FeedbackItemResolution(
                            MutationErrorFeedbackItemProvider::class,
                            MutationErrorFeedbackItemProvider::E2,
                        ),
                        $field,
                    )
                );
            }
            if (!$fieldDataAccessor->getValue(MutationInputProperties::AUTHOR_EMAIL)) {
                $objectTypeFieldResolutionFeedbackStore->addError(
                    new ObjectTypeFieldResolutionFeedback(
                        new FeedbackItemResolution(
                            MutationErrorFeedbackItemProvider::class,
                            MutationErrorFeedbackItemProvider::E3,
                        ),
                        $field,
                    )
                );
            }
        }

        // Either provide the customPostID, or retrieve it from the parent comment
        if (!$fieldDataAccessor->getValue(MutationInputProperties::CUSTOMPOST_ID) && !$fieldDataAccessor->getValue(MutationInputProperties::PARENT_COMMENT_ID)) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        MutationErrorFeedbackItemProvider::class,
                        MutationErrorFeedbackItemProvider::E4,
                    ),
                    $field,
                )
            );
        }
        // Make sure the parent comment exists
        // Either provide the customPostID, or retrieve it from the parent comment
        if ($parentCommentID = $fieldDataAccessor->getValue(MutationInputProperties::PARENT_COMMENT_ID)) {
            $parentComment = $this->getCommentTypeAPI()->getComment($parentCommentID);
            if ($parentComment === null) {
                $objectTypeFieldResolutionFeedbackStore->addError(
                    new ObjectTypeFieldResolutionFeedback(
                        new FeedbackItemResolution(
                            MutationErrorFeedbackItemProvider::class,
                            MutationErrorFeedbackItemProvider::E6,
                            [
                                $parentCommentID,
                            ]
                        ),
                        $field,
                    )
                );
            }
        }
        if (!$fieldDataAccessor->getValue(MutationInputProperties::COMMENT)) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        MutationErrorFeedbackItemProvider::class,
                        MutationErrorFeedbackItemProvider::E5,
                    ),
                    $field,
                )
            );
        }
    }

    protected function getUserNotLoggedInError(): FeedbackItemResolution
    {
        return new FeedbackItemResolution(
            MutationErrorFeedbackItemProvider::class,
            MutationErrorFeedbackItemProvider::E1,
        );
    }

    protected function additionals(string|int $comment_id, FieldDataAccessorInterface $fieldDataAccessor): void
    {
        App::doAction('gd_addcomment', $comment_id, $fieldDataAccessor);
    }

    /**
     * @return array<string,mixed>
     */
    protected function getCommentData(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        $comment_data = [
            'authorIP' => App::server('REMOTE_ADDR'),
            'agent' => App::server('HTTP_USER_AGENT'),
            'content' => $fieldDataAccessor->getValue(MutationInputProperties::COMMENT),
            'parent' => $fieldDataAccessor->getValue(MutationInputProperties::PARENT_COMMENT_ID),
            'customPostID' => $fieldDataAccessor->getValue(MutationInputProperties::CUSTOMPOST_ID),
        ];
        /**
         * Override with the user's properties
         */
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($moduleConfiguration->mustUserBeLoggedInToAddComment()) {
            $userID = App::getState('current-user-id');
            $comment_data['userID'] = $userID;
            $comment_data['author'] = $this->getUserTypeAPI()->getUserDisplayName($userID);
            $comment_data['authorEmail'] = $this->getUserTypeAPI()->getUserEmail($userID);
            $comment_data['authorURL'] = $this->getUserTypeAPI()->getUserWebsiteURL($userID);
        } else {
            if ($userID = App::getState('current-user-id')) {
                $comment_data['userID'] = $userID;
            }
            $comment_data['author'] = $fieldDataAccessor->getValue(MutationInputProperties::AUTHOR_NAME);
            $comment_data['authorEmail'] = $fieldDataAccessor->getValue(MutationInputProperties::AUTHOR_EMAIL);
            $comment_data['authorURL'] = $fieldDataAccessor->getValue(MutationInputProperties::AUTHOR_URL);
        }

        // If the parent comment is provided and the custom post is not,
        // then retrieve it from there
        if ($comment_data['parent'] && !$comment_data['customPostID']) {
            /** @var object */
            $parentComment = $this->getCommentTypeAPI()->getComment($comment_data['parent']);
            $comment_data['customPostID'] = $this->getCommentTypeAPI()->getCommentPostID($parentComment);
        }

        return $comment_data;
    }

    /**
     * @throws CommentCRUDMutationException In case of error
     * @param array<string,mixed> $comment_data
     */
    protected function insertComment(array $comment_data): string|int
    {
        return $this->getCommentTypeMutationAPI()->insertComment($comment_data);
    }

    /**
     * @throws AbstractException In case of error
     */
    public function executeMutation(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $comment_data = $this->getCommentData($fieldDataAccessor);
        $comment_id = $this->insertComment($comment_data);

        // Allow for additional operations (eg: set Action categories)
        $this->additionals($comment_id, $fieldDataAccessor);

        return $comment_id;
    }
}
