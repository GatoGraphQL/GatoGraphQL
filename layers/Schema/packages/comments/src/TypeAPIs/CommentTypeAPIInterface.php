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
}
