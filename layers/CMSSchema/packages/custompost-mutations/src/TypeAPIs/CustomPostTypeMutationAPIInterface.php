<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\TypeAPIs;

use PoPCMSSchema\CustomPostMutations\Exception\CustomPostCRUDMutationException;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface CustomPostTypeMutationAPIInterface
{
    /**
     * @param array<string,mixed> $data
     * @return string|int the ID of the created custom post
     * @throws CustomPostCRUDMutationException If there was an error (eg: some Custom Post creation validation failed)
     */
    public function createCustomPost(array $data): string|int;
    /**
     * @param array<string,mixed> $data
     * @return string|int the ID of the updated custom post
     * @throws CustomPostCRUDMutationException If there was an error (eg: Custom Post does not exist)
     */
    public function updateCustomPost(array $data): string|int;
    /**
     * Send the custom post to the trash, from where it can be restored.
     *
     * @throws CustomPostCRUDMutationException If there was an error (eg: the custom post could not be trashed)
     */
    public function trashCustomPost(string|int $customPostID): void;
    /**
     * Permanently delete the custom post, bypassing the trash.
     *
     * @throws CustomPostCRUDMutationException If there was an error (eg: the custom post could not be deleted)
     */
    public function deleteCustomPost(string|int $customPostID): void;
    /**
     * Whether the custom post type can be sent to the trash, or must
     * be permanently deleted.
     */
    public function doesCustomPostTypeSupportTrash(string $customPostType): bool;
    public function canUserEditCustomPost(string|int $userID, string|int $customPostID): bool;
    public function canUserEditCustomPostType(string|int $userID, string $customPostType): bool;
    public function canUserDeleteCustomPost(string|int $userID, string|int $customPostID): bool;
    public function canUserDeleteCustomPostType(string|int $userID, string $customPostType): bool;
}
