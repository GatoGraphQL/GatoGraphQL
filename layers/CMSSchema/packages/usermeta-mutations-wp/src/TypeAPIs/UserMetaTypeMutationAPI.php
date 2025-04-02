<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutationsWP\TypeAPIs;

use PoPCMSSchema\UserMetaMutations\TypeAPIs\AbstractUserMetaTypeMutationAPI;
use WP_Error;

use function add_user_meta;
use function delete_user_meta;
use function update_user_meta;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class UserMetaTypeMutationAPI extends AbstractUserMetaTypeMutationAPI
{
    protected function executeAddEntityMeta(
        string|int $entityID,
        string $key,
        mixed $value,
        bool $single = false,
    ): int|false|WP_Error {
        return add_user_meta((int) $entityID, $key, $value, $single);
    }

    protected function executeUpdateEntityMeta(
        string|int $entityID,
        string $key,
        mixed $value,
        mixed $prevValue = null,
    ): int|bool|WP_Error {
        return update_user_meta((int) $entityID, $key, $value, $prevValue ?? '');
    }

    protected function executeDeleteEntityMeta(
        string|int $entityID,
        string $key,
    ): bool {
        return delete_user_meta((int) $entityID, $key);
    }
}
