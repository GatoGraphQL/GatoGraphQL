<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutationsWP\TypeAPIs;

use PoPCMSSchema\CustomPostMetaMutations\TypeAPIs\AbstractCustomPostMetaTypeMutationAPI;
use PoPCMSSchema\CustomPostMetaMutations\Exception\CustomPostMetaCRUDMutationException;

use function add_post_meta;
use function delete_post_meta;
use function update_post_meta;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class CustomPostMetaTypeMutationAPI extends AbstractCustomPostMetaTypeMutationAPI
{
    /**
     * @param array<string,mixed[]|null> $entries
     * @throws CustomPostMetaCRUDMutationException If there was an error
     */
    public function setCustomPostMeta(
        string|int $customPostID,
        array $entries,
    ): void {
        foreach ($entries as $key => $values) {
            if ($values === null) {
                delete_post_meta((int) $customPostID, $key);
                continue;
            }

            $numberItems = count($values);
            if ($numberItems === 0) {
                continue;
            }

            /**
             * If there are 2 or more items, then use `add` to add them.
             * If there is only 1 item, then use `update` to update it.
             */
            if ($numberItems === 1) {
                $value = $values[0];
                if ($value === null) {
                    delete_post_meta((int) $customPostID, $key);
                    continue;
                }
                update_post_meta((int) $customPostID, $key, $value);
                continue;
            }

            // $numberItems > 1
            delete_post_meta((int) $customPostID, $key);
            foreach ($values as $value) {
                add_post_meta((int) $customPostID, $key, $value, false);
            }
        }
    }

    /**
     * @return int The term_id of the newly created term
     * @throws CustomPostMetaCRUDMutationException If there was an error
     */
    public function addCustomPostMeta(
        string|int $customPostID,
        string $key,
        mixed $value,
        bool $single = false,
    ): int {
        $result = add_post_meta((int) $customPostID, $key, $value, $single);
        if ($result === false) {
            throw $this->getEntityMetaCRUDMutationException(
                \__('Error adding custom post meta', 'custompostmeta-mutations-wp')
            );
        }
        $this->handleMaybeError($result);
        /** @var int $result */
        return $result;
    }

    /**
     * @return string|int|bool the ID of the created meta entry if it didn't exist, or `true` if it did exist
     * @throws CustomPostMetaCRUDMutationException If there was an error (eg: custom post does not exist)
     */
    public function updateCustomPostMeta(
        string|int $customPostID,
        string $key,
        mixed $value,
        mixed $prevValue = null,
    ): string|int|bool {
        $result = update_post_meta((int) $customPostID, $key, $value, $prevValue ?? '');
        $this->handleMaybeError($result);
        if ($result === false) {
            throw $this->getEntityMetaCRUDMutationException(
                \__('Error updating custom post meta', 'custompostmeta-mutations-wp')
            );
        }
        /** @var int|bool $result */
        return $result;
    }

    /**
     * @throws CustomPostMetaCRUDMutationException If there was an error (eg: custom post does not exist)
     */
    public function deleteCustomPostMeta(
        string|int $customPostID,
        string $key,
    ): void {
        $result = delete_post_meta((int) $customPostID, $key);
        $this->handleMaybeError($result);
        if ($result === false) {
            throw $this->getEntityMetaCRUDMutationException(
                \__('Error deleting custom post meta', 'custompostmeta-mutations-wp')
            );
        }
    }
}
