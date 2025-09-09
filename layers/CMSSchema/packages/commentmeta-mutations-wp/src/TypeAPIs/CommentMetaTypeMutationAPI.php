<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutationsWP\TypeAPIs;

use PoPCMSSchema\CommentMetaMutations\TypeAPIs\AbstractCommentMetaTypeMutationAPI;
use PoPCMSSchema\MetaMutationsWP\TypeAPIs\EntityMetaTypeMutationAPITrait;
use WP_Error;

use function add_comment_meta;
use function delete_comment_meta;
use function update_comment_meta;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class CommentMetaTypeMutationAPI extends AbstractCommentMetaTypeMutationAPI
{
    use EntityMetaTypeMutationAPITrait;

    protected function executeAddEntityMeta(
        string|int $entityID,
        string $key,
        mixed $value,
        bool $single = false,
    ): int|false|WP_Error {
        return add_comment_meta((int) $entityID, $key, $value, $single);
    }

    protected function executeUpdateEntityMeta(
        string|int $entityID,
        string $key,
        mixed $value,
        mixed $prevValue = null,
    ): int|bool|WP_Error {
        return update_comment_meta((int) $entityID, $key, $value, $prevValue ?? '');
    }

    protected function executeDeleteEntityMeta(
        string|int $entityID,
        string $key,
        mixed $value = null,
    ): bool {
        return delete_comment_meta((int) $entityID, $key, $value ?? '');
    }
}
