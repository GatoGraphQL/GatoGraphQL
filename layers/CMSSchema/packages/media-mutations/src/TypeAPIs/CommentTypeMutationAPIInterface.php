<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\TypeAPIs;

use PoPCMSSchema\MediaMutations\Exception\CommentCRUDMutationException;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface MediaTypeMutationAPIInterface
{
    /**
     * @throws CommentCRUDMutationException In case of error
     * @param array<string,mixed> $comment_data
     */
    public function insertComment(array $comment_data): string|int;
}
