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
    public function getComment(string | int $comment_id): ?object;
    public function getCommentNumber(string | int $post_id): int;
    public function areCommentsOpen(string | int $post_id): bool;
}
