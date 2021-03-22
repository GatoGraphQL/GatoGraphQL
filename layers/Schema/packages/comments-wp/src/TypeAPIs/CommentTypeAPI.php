<?php

declare(strict_types=1);

namespace PoPSchema\CommentsWP\TypeAPIs;

use WP_Comment;
use PoPSchema\Comments\TypeAPIs\CommentTypeAPIInterface;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class CommentTypeAPI implements CommentTypeAPIInterface
{
    /**
     * Indicates if the passed object is of type Comment
     */
    public function isInstanceOfCommentType(object $object): bool
    {
        return $object instanceof WP_Comment;
    }
}
