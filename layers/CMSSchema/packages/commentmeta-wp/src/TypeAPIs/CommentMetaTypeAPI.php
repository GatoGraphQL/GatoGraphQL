<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaWP\TypeAPIs;

use PoPCMSSchema\CommentMeta\TypeAPIs\AbstractCommentMetaTypeAPI;

class CommentMetaTypeAPI extends AbstractCommentMetaTypeAPI
{
    /**
     * If the key is non-existent, return `null`.
     * Otherwise, return the value.
     */
    protected function doGetCommentMeta(string | int $commentID, string $key, bool $single = false): mixed
    {
        // This function does not differentiate between a stored empty value,
        // and a non-existing key! So if empty, treat it as non-existant and return null
        $value = \get_comment_meta($commentID, $key, $single);
        if (($single && $value === '') || (!$single && $value === [])) {
            return null;
        }
        return $value;
    }
}
