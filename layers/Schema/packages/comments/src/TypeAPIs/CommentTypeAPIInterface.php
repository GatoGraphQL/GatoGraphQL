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
     *
     * @param [type] $object
     */
    public function isInstanceOfCommentType($object): bool;
}
