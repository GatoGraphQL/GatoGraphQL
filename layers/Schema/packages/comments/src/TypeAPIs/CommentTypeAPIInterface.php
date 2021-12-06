<?php

declare(strict_types=1);

namespace PoPSchema\Comments\TypeAPIs;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface CommentTypeAPIInterface
{
    /**
     * Indicates if the passed object is of type Comment
     */
    public function isInstanceOfCommentType(object $object): bool;

    public function getComments(array $query, array $options = []): array;
    public function getCommentCount(array $query, array $options = []): int;
    public function getComment(string | int $comment_id): ?object;
    public function getCommentNumber(string | int $post_id): int;
    public function areCommentsOpen(string | int $post_id): bool;

    public function getCommentContent(object $comment): string;
    public function getCommentRawContent(object $comment): string;
    public function getCommentPostId(object $comment): int | string;
    public function isCommentApproved(object $comment): bool;
    public function getCommentType(object $comment): string;
    public function getCommentStatus(object $comment): string;
    public function getCommentParent(object $comment): int | string | null;
    public function getCommentDate(object $comment, bool $gmt = false): string;
    public function getCommentId(object $comment): string | int;
    public function getCommentAuthorName(object $comment): string;
    public function getCommentAuthorEmail(object $comment): string;
    public function getCommentAuthorURL(object $comment): string;
}
