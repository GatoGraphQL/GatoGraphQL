<?php

declare(strict_types=1);

namespace PoPSchema\CommentMetaWP\TypeAPIs;

use PoPSchema\CommentMeta\TypeAPIs\AbstractCommentMetaTypeAPI;

class CommentMetaTypeAPI extends AbstractCommentMetaTypeAPI
{
    public function doGetCommentMeta(string | int $commentID, string $key, bool $single = false): mixed
    {
        return \get_comment_meta($commentID, $key, $single);
    }
}
