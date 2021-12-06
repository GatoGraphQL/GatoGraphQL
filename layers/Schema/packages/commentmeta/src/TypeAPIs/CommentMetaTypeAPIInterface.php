<?php

declare(strict_types=1);

namespace PoPSchema\CommentMeta\TypeAPIs;

use InvalidArgumentException;

interface CommentMetaTypeAPIInterface
{
    /**
     * If the allow/denylist validation fails, throw an exception.
     * If the key is allowed but non-existent, return `null`.
     * Otherwise, return the value.
     *
     * @throws InvalidArgumentException
     */
    public function getCommentMeta(string | int $commentID, string $key, bool $single = false): mixed;
}
