<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\MutationResolvers;

use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\WithArgumentsInterface;
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
        return $this->commentTypeAPI ??= $this->instanceManager->getInstance(CommentTypeAPIInterface::class);
    }
    final public function setCommentTypeMutationAPI(CommentTypeMutationAPIInterface $commentTypeMutationAPI): void
    {
        $this->commentTypeMutationAPI = $commentTypeMutationAPI;
    }
    final protected function getCommentTypeMutationAPI(): CommentTypeMutationAPIInterface
    {
        return $this->commentTypeMutationAPI ??= $this->instanceManager->getInstance(CommentTypeMutationAPIInterface::class);
    }
    final public function setUserTypeAPI(UserTypeAPIInterface $userTypeAPI): void
    {
        $this->userTypeAPI = $userTypeAPI;
    }
    final protected function getUserTypeAPI(): UserTypeAPIInterface
    {
        return $this->userTypeAPI ??= $this->instanceManager->getInstance(UserTypeAPIInterface::class);
    }

    public function validateErrors(\PoP\ComponentModel\Mutation\MutationDataProviderInterface $mutationDataProvider): array
    {
        $errors = [];

        // Check that the user is logged-in
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($moduleConfiguration->mustUserBeLoggedInToAddComment()) {
            $errorFeedbackItemResolution = $this->validateUserIsLoggedIn();
            if ($errorFeedbackItemResolution !== null) {
                return [
                    $errorFeedbackItemResolution,
                ];
            }
        } elseif ($moduleConfiguration->requireCommenterNameAndEmail()) {
            // Validate if the commenter's name and email are mandatory
            if (!$mutationDataProvider->getArgumentValue(MutationInputProperties::AUTHOR_NAME)) {
                $errors[] = new FeedbackItemResolution(
                    MutationErrorFeedbackItemProvider::class,
                    MutationErrorFeedbackItemProvider::E2,
                );
            }
            if (!$mutationDataProvider->getArgumentValue(MutationInputProperties::AUTHOR_EMAIL)) {
                $errors[] = new FeedbackItemResolution(
                    MutationErrorFeedbackItemProvider::class,
                    MutationErrorFeedbackItemProvider::E3,
                );
            }
        }

        // Either provide the customPostID, or retrieve it from the parent comment
        if (!$mutationDataProvider->getArgumentValue(MutationInputProperties::CUSTOMPOST_ID) && !$mutationDataProvider->getArgumentValue(MutationInputProperties::PARENT_COMMENT_ID)) {
            $errors[] = new FeedbackItemResolution(
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E4,
            );
        }
        if (!$mutationDataProvider->getArgumentValue(MutationInputProperties::COMMENT)) {
            $errors[] = new FeedbackItemResolution(
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E5,
            );
        }
        return $errors;
    }

    protected function getUserNotLoggedInError(): FeedbackItemResolution
    {
        return new FeedbackItemResolution(
            MutationErrorFeedbackItemProvider::class,
            MutationErrorFeedbackItemProvider::E1,
        );
    }

    protected function additionals(string | int $comment_id, \PoP\ComponentModel\Mutation\MutationDataProviderInterface $mutationDataProvider): void
    {
        App::doAction('gd_addcomment', $comment_id, $mutationDataProvider);
    }

    protected function getCommentData(\PoP\ComponentModel\Mutation\MutationDataProviderInterface $mutationDataProvider): array
    {
        $comment_data = [
            'authorIP' => App::server('REMOTE_ADDR'),
            'agent' => App::server('HTTP_USER_AGENT'),
            'content' => $mutationDataProvider->getArgumentValue(MutationInputProperties::COMMENT),
            'parent' => $mutationDataProvider->getArgumentValue(MutationInputProperties::PARENT_COMMENT_ID),
            'customPostID' => $mutationDataProvider->getArgumentValue(MutationInputProperties::CUSTOMPOST_ID),
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
            $comment_data['author'] = $mutationDataProvider->getArgumentValue(MutationInputProperties::AUTHOR_NAME);
            $comment_data['authorEmail'] = $mutationDataProvider->getArgumentValue(MutationInputProperties::AUTHOR_EMAIL);
            $comment_data['authorURL'] = $mutationDataProvider->getArgumentValue(MutationInputProperties::AUTHOR_URL);
        }

        // If the parent comment is provided and the custom post is not,
        // then retrieve it from there
        if ($comment_data['parent'] && !$comment_data['customPostID']) {
            $parentComment = $this->getCommentTypeAPI()->getComment($comment_data['parent']);
            $comment_data['customPostID'] = $this->getCommentTypeAPI()->getCommentPostId($parentComment);
        }

        return $comment_data;
    }

    /**
     * @throws CommentCRUDMutationException In case of error
     */
    protected function insertComment(array $comment_data): string | int
    {
        return $this->getCommentTypeMutationAPI()->insertComment($comment_data);
    }

    /**
     * @throws AbstractException In case of error
     */
    public function executeMutation(\PoP\ComponentModel\Mutation\MutationDataProviderInterface $mutationDataProvider): mixed
    {
        $comment_data = $this->getCommentData($mutationDataProvider);
        $comment_id = $this->insertComment($comment_data);

        // Allow for additional operations (eg: set Action categories)
        $this->additionals($comment_id, $mutationDataProvider);

        return $comment_id;
    }
}
