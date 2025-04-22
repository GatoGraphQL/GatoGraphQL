<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\TypeAPIs;

use PoPCMSSchema\MetaMutations\TypeAPIs\EntityMetaTypeMutationAPIInterface;
use PoPCMSSchema\CommentMetaMutations\Exception\CommentMetaCRUDMutationException;

interface CommentMetaTypeMutationAPIInterface extends EntityMetaTypeMutationAPIInterface
{
    /**
     * @param array<string,mixed[]|null> $entries
     * @throws CommentMetaCRUDMutationException If there was an error
     */
    public function setCommentMeta(
        string|int $commentID,
        array $entries,
    ): void;

    /**
     * @return int The term_id of the newly created term
     * @throws CommentMetaCRUDMutationException If there was an error
     */
    public function addCommentMeta(
        string|int $commentID,
        string $key,
        mixed $value,
        bool $single = false,
    ): int;

    /**
     * @return string|int|bool the ID of the created meta entry if it didn't exist, or `true` if it did exist
     * @throws CommentMetaCRUDMutationException If there was an error (eg: comment does not exist)
     */
    public function updateCommentMeta(
        string|int $commentID,
        string $key,
        mixed $value,
        mixed $prevValue = null,
    ): string|int|bool;

    /**
     * @throws CommentMetaCRUDMutationException If there was an error (eg: comment does not exist)
     */
    public function deleteCommentMeta(
        string|int $commentID,
        string $key,
        mixed $value = null,
    ): void;
}
