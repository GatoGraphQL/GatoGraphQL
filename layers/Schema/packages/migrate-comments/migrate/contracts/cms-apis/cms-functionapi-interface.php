<?php
namespace PoPSchema\Comments;

interface FunctionAPI
{
    public function getComments($query, array $options = []): array;
    public function getComment($comment_id);
    public function getCommentNumber($post_id): int;
    public function areCommentsOpen($post_id): bool;
}
