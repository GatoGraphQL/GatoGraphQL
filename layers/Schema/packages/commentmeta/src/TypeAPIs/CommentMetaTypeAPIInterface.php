<?php

declare(strict_types=1);

namespace PoPSchema\CommentMeta\TypeAPIs;

interface CommentMetaTypeAPIInterface
{
    public function getCommentMeta(string | int $commentID, string $key, bool $single = false): mixed;
}
