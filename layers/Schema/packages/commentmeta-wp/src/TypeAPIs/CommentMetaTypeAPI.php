<?php

declare(strict_types=1);

namespace PoPSchema\CommentMetaWP\TypeAPIs;

use PoPSchema\CommentMeta\TypeAPIs\CommentMetaTypeAPIInterface;

class CommentMetaTypeAPI implements CommentMetaTypeAPIInterface
{
    public function getCommentMeta(string | int $commentID, string $key, bool $single = false): mixed
    {
        return \get_comment_meta($commentID, $key, $single);
    }
}
