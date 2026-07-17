<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutationsWP\TypeAPIs;

use PoP\Root\Services\AbstractBasicService;
use PoPCMSSchema\CommentMutations\Exception\CommentCRUDMutationException;
use PoPCMSSchema\CommentMutations\TypeAPIs\CommentTypeMutationAPIInterface;
use PoPCMSSchema\Comments\Constants\CommentStatus;
use WP_Error;

use function get_comment;
use function is_wp_error;
use function user_can;
use function wp_delete_comment;
use function wp_set_comment_status;
use function wp_trash_comment;
use function wp_update_comment;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class CommentTypeMutationAPI extends AbstractBasicService implements CommentTypeMutationAPIInterface
{
    /**
     * @throws CommentCRUDMutationException In case of error
     * @param array<string,mixed> $comment_data
     */
    public function insertComment(array $comment_data): string|int
    {
        // Convert the parameters
        if (isset($comment_data['userID'])) {
            $comment_data['user_id'] = $comment_data['userID'];
            unset($comment_data['userID']);
        }
        if (isset($comment_data['author'])) {
            $comment_data['comment_author'] = $comment_data['author'];
            unset($comment_data['author']);
        }
        if (isset($comment_data['authorEmail'])) {
            $comment_data['comment_author_email'] = $comment_data['authorEmail'];
            unset($comment_data['authorEmail']);
        }
        if (isset($comment_data['authorURL'])) {
            $comment_data['comment_author_url'] = $comment_data['authorURL'];
            unset($comment_data['authorURL']);
        }
        if (isset($comment_data['authorIP'])) {
            $comment_data['comment_author_IP'] = $comment_data['authorIP'];
            unset($comment_data['authorIP']);
        }
        if (isset($comment_data['agent'])) {
            $comment_data['comment_agent'] = $comment_data['agent'];
            unset($comment_data['agent']);
        }
        if (isset($comment_data['content'])) {
            $comment_data['comment_content'] = $comment_data['content'];
            unset($comment_data['content']);
        }
        if (isset($comment_data['parent'])) {
            $comment_data['comment_parent'] = $comment_data['parent'];
            unset($comment_data['parent']);
        }
        if (isset($comment_data['customPostID'])) {
            $comment_data['comment_post_ID'] = $comment_data['customPostID'];
            unset($comment_data['customPostID']);
        }
        $commentID = \wp_insert_comment($comment_data);
        if ($commentID === false) {
            throw new CommentCRUDMutationException(
                $this->__('Could not create the comment', 'gatographql')
            );
        }
        return $commentID;
    }

    /**
     * @throws CommentCRUDMutationException In case of error
     * @param array<string,mixed> $comment_data
     */
    public function updateComment(
        string|int $commentID,
        array $comment_data,
    ): void {
        if ($comment_data === []) {
            return;
        }

        $comment_data = $this->convertCommentEditionArgs($comment_data);
        $comment_data['comment_ID'] = $commentID;

        $resultOrError = wp_update_comment($comment_data, true);

        if (is_wp_error($resultOrError)) {
            /** @var WP_Error */
            $wpError = $resultOrError;
            throw new CommentCRUDMutationException(
                $wpError->get_error_message()
            );
        }

        if ($resultOrError === 0) {
            throw new CommentCRUDMutationException(
                sprintf(
                    $this->__('The comment with ID \'%s\' could not be updated', 'gatographql'),
                    $commentID
                )
            );
        }
    }

    /**
     * @param array<string,mixed> $comment_data
     * @return array<string,mixed>
     */
    protected function convertCommentEditionArgs(array $comment_data): array
    {
        if (isset($comment_data['content'])) {
            $comment_data['comment_content'] = $comment_data['content'];
            unset($comment_data['content']);
        }
        if (isset($comment_data['author'])) {
            $comment_data['comment_author'] = $comment_data['author'];
            unset($comment_data['author']);
        }
        if (isset($comment_data['authorEmail'])) {
            $comment_data['comment_author_email'] = $comment_data['authorEmail'];
            unset($comment_data['authorEmail']);
        }
        if (isset($comment_data['authorURL'])) {
            $comment_data['comment_author_url'] = $comment_data['authorURL'];
            unset($comment_data['authorURL']);
        }
        return $comment_data;
    }

    /**
     * `wp_set_comment_status` is used instead of `wp_update_comment`, as it
     * is the API to moderate a comment: it moves the comment to the trash
     * or the spam queue, and triggers the corresponding hooks.
     *
     * @throws CommentCRUDMutationException In case of error
     */
    public function setCommentStatus(
        string|int $commentID,
        string $commentStatus,
    ): void {
        $resultOrError = wp_set_comment_status((int) $commentID, $commentStatus, true);

        if (is_wp_error($resultOrError)) {
            /** @var WP_Error */
            $wpError = $resultOrError;
            throw new CommentCRUDMutationException(
                $wpError->get_error_message()
            );
        }

        if ($resultOrError === false) {
            throw new CommentCRUDMutationException(
                sprintf(
                    $this->__('The status of the comment with ID \'%s\' could not be updated', 'gatographql'),
                    $commentID
                )
            );
        }
    }

    /**
     * @throws CommentCRUDMutationException In case of error
     */
    public function trashComment(
        string|int $commentID,
    ): void {
        if (!wp_trash_comment((int) $commentID)) {
            throw new CommentCRUDMutationException(
                sprintf(
                    $this->__('The comment with ID \'%s\' could not be sent to the trash', 'gatographql'),
                    $commentID
                )
            );
        }
    }

    /**
     * @throws CommentCRUDMutationException In case of error
     */
    public function deleteComment(
        string|int $commentID,
    ): void {
        if (!wp_delete_comment((int) $commentID, true)) {
            throw new CommentCRUDMutationException(
                sprintf(
                    $this->__('The comment with ID \'%s\' could not be deleted', 'gatographql'),
                    $commentID
                )
            );
        }
    }

    public function doesCommentSupportTrash(): bool
    {
        return EMPTY_TRASH_DAYS > 0;
    }

    public function isCommentInTrash(
        string|int $commentID,
    ): bool {
        $comment = get_comment((int) $commentID);
        if ($comment === null) {
            return false;
        }
        return $comment->comment_approved === CommentStatus::TRASH;
    }

    public function canUserEditComment(
        string|int $userID,
        string|int $commentID,
    ): bool {
        return user_can((int) $userID, 'edit_comment', $commentID);
    }
}
