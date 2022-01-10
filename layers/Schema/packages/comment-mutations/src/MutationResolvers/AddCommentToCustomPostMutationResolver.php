<?php

declare(strict_types=1);

namespace PoPSchema\CommentMutations\MutationResolvers;

use PoP\Root\App;
use PoP\ComponentModel\Error\Error;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoP\ComponentModel\State\ApplicationState;
use PoPSchema\CommentMutations\Component;
use PoPSchema\CommentMutations\ComponentConfiguration;
use PoPSchema\CommentMutations\TypeAPIs\CommentTypeMutationAPIInterface;
use PoPSchema\Comments\TypeAPIs\CommentTypeAPIInterface;
use PoPSchema\Users\TypeAPIs\UserTypeAPIInterface;
use PoPSchema\UserStateMutations\MutationResolvers\ValidateUserLoggedInMutationResolverTrait;

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

    public function validateErrors(array $form_data): array
    {
        $errors = [];

        // Check that the user is logged-in
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        if ($componentConfiguration->mustUserBeLoggedInToAddComment()) {
            $this->validateUserIsLoggedIn($errors);
            if ($errors) {
                return $errors;
            }
        } elseif ($componentConfiguration->requireCommenterNameAndEmail()) {
            // Validate if the commenter's name and email are mandatory
            if (!($form_data[MutationInputProperties::AUTHOR_NAME] ?? null)) {
                $errors[] = $this->__('The comment author\'s name is missing', 'comment-mutations');
            }
            if (!($form_data[MutationInputProperties::AUTHOR_EMAIL] ?? null)) {
                $errors[] = $this->__('The comment author\'s email is missing', 'comment-mutations');
            }
        }

        // Either provide the customPostID, or retrieve it from the parent comment
        if (!($form_data[MutationInputProperties::CUSTOMPOST_ID] ?? null) && !($form_data[MutationInputProperties::PARENT_COMMENT_ID] ?? null)) {
            $errors[] = $this->__('The custom post ID is missing.', 'comment-mutations');
        }
        if (!($form_data[MutationInputProperties::COMMENT] ?? null)) {
            $errors[] = $this->__('The comment is empty.', 'comment-mutations');
        }
        return $errors;
    }

    protected function getUserNotLoggedInErrorMessage(): string
    {
        return $this->__('You must be logged in to add comments', 'comment-mutations');
    }

    protected function additionals(string | int $comment_id, array $form_data): void
    {
        $this->getHooksAPI()->doAction('gd_addcomment', $comment_id, $form_data);
    }

    protected function getCommentData(array $form_data): array
    {
        $comment_data = [
            'authorIP' => $_SERVER['REMOTE_ADDR'] ?? null,
            'agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
            'content' => $form_data[MutationInputProperties::COMMENT],
            'parent' => $form_data[MutationInputProperties::PARENT_COMMENT_ID] ?? null,
            'customPostID' => $form_data[MutationInputProperties::CUSTOMPOST_ID] ?? null,
        ];
        /**
         * Override with the user's properties
         */
        $vars = ApplicationState::getVars();
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        if ($componentConfiguration->mustUserBeLoggedInToAddComment()) {
            $userID = $vars['current-user-id'];
            $comment_data['userID'] = $userID;
            $comment_data['author'] = $this->getUserTypeAPI()->getUserDisplayName($userID);
            $comment_data['authorEmail'] = $this->getUserTypeAPI()->getUserEmail($userID);
            $comment_data['authorURL'] = $this->getUserTypeAPI()->getUserWebsiteUrl($userID);
        } else {
            if ($userID = $vars['current-user-id'] ?? null) {
                $comment_data['userID'] = $userID;
            }
            $comment_data['author'] = $form_data[MutationInputProperties::AUTHOR_NAME] ?? null;
            $comment_data['authorEmail'] = $form_data[MutationInputProperties::AUTHOR_EMAIL] ?? null;
            $comment_data['authorURL'] = $form_data[MutationInputProperties::AUTHOR_URL] ?? null;
        }

        // If the parent comment is provided and the custom post is not,
        // then retrieve it from there
        if ($comment_data['parent'] && !$comment_data['customPostID']) {
            $parentComment = $this->getCommentTypeAPI()->getComment($comment_data['parent']);
            $comment_data['customPostID'] = $this->getCommentTypeAPI()->getCommentPostId($parentComment);
        }

        return $comment_data;
    }

    protected function insertComment(array $comment_data): string | int | Error
    {
        return $this->getCommentTypeMutationAPI()->insertComment($comment_data);
    }

    public function executeMutation(array $form_data): mixed
    {
        $comment_data = $this->getCommentData($form_data);
        $comment_id = $this->insertComment($comment_data);
        if (GeneralUtils::isError($comment_id)) {
            return $comment_id;
        }

        // Allow for additional operations (eg: set Action categories)
        $this->additionals($comment_id, $form_data);

        return $comment_id;
    }
}
