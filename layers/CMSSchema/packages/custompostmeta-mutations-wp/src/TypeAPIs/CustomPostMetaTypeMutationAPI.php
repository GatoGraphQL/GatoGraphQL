<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutationsWP\TypeAPIs;

use PoPCMSSchema\CustomPostMetaMutations\TypeAPIs\AbstractCustomPostMetaTypeMutationAPI;
use WP_Error;

use function add_post_meta;
use function delete_post_meta;
use function update_post_meta;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class CustomPostMetaTypeMutationAPI extends AbstractCustomPostMetaTypeMutationAPI
{
    protected function executeAddEntityMeta(
        string|int $entityID,
        string $key,
        mixed $value,
        bool $single = false,
    ): int|false|WP_Error {
        return add_post_meta((int) $entityID, $key, $value, $single);
    }

    protected function executeUpdateEntityMeta(
        string|int $entityID,
        string $key,
        mixed $value,
        mixed $prevValue = null,
    ): int|bool|WP_Error {
        return update_post_meta((int) $entityID, $key, $value, $prevValue ?? '');
    }

    protected function executeDeleteEntityMeta(
        string|int $entityID,
        string $key,
        mixed $value = null,
    ): bool {
        return delete_post_meta((int) $entityID, $key, $value ?? '');
    }
}
