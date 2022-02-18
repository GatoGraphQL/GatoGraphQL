<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\TypeAPIs;

use PoPCMSSchema\CommentMutations\Exception\CommentCRUDException;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface CommentTypeMutationAPIInterface
{
    /**
     * @throws CommentCRUDException In case of error
     */
    public function insertComment(array $comment_data): string | int
}
