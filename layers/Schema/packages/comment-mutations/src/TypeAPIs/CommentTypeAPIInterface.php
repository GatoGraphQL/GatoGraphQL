<?php

declare(strict_types=1);

namespace PoPSchema\CommentMutations\TypeAPIs;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface CommentTypeAPIInterface
{
    /**
     * @return mixed
     */
    public function insertComment(array $comment_data);
}
