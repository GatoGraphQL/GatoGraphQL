<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\TypeAPIs;

use PoPCMSSchema\CommentMutations\Exception\CommentCRUDMutationException;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface CommentTypeMutationAPIInterface
{
    /**
     * @throws CommentCRUDMutationException In case of error
     * @param array<string,mixed> $comment_data
     */
    public function insertComment(array $comment_data): string|int;
    /**
     * @throws CommentCRUDMutationException In case of error
     * @param array<string,mixed> $comment_data
     */
    public function updateComment(
        string|int $commentID,
        array $comment_data,
    ): void;
    /**
     * Moderate the comment: approve it, hold it for moderation,
     * mark it as spam, or send it to the trash.
     *
     * @throws CommentCRUDMutationException In case of error
     */
    public function setCommentStatus(
        string|int $commentID,
        string $commentStatus,
    ): void;
    /**
     * @throws CommentCRUDMutationException In case of error
     */
    public function trashComment(
        string|int $commentID,
    ): void;
    /**
     * @throws CommentCRUDMutationException In case of error
     */
    public function deleteComment(
        string|int $commentID,
    ): void;
    /**
     * Whether comments can be sent to the trash, or must always
     * be deleted permanently.
     */
    public function doesCommentSupportTrash(): bool;
    public function isCommentInTrash(
        string|int $commentID,
    ): bool;
    /**
     * Whether the user can edit (and moderate, and delete) the comment.
     *
     * The CMS resolves this meta capability against the comment's
     * custom post, so it also covers the ownership of that custom post.
     */
    public function canUserEditComment(
        string|int $userID,
        string|int $commentID,
    ): bool;
}
