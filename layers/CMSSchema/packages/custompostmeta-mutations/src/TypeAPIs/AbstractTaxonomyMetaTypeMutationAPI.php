<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\TypeAPIs;

use PoPCMSSchema\CustomPostMetaMutations\Exception\CustomPostMetaCRUDMutationException;
use PoPCMSSchema\CustomPostMetaMutations\TypeAPIs\CustomPostMetaTypeMutationAPIInterface;
use PoP\Root\Services\AbstractBasicService;

abstract class AbstractCustomPostMetaTypeMutationAPI extends AbstractBasicService implements CustomPostMetaTypeMutationAPIInterface
{
    /**
     * @param array<string,mixed[]|null> $entries
     * @throws CustomPostMetaCRUDMutationException If there was an error
     */
    public function setTermMeta(
        string|int $customPostID,
        array $entries,
    ): void {
        $this->setCustomPostMeta(
            $customPostID,
            $entries,
        );
    }

    /**
     * @return int The term_id of the newly created term
     * @throws CustomPostMetaCRUDMutationException If there was an error
     */
    public function addTermMeta(
        string|int $customPostID,
        string $key,
        mixed $value,
        bool $single = false,
    ): int {
        return $this->addCustomPostMeta(
            $customPostID,
            $key,
            $value,
            $single,
        );
    }

    /**
     * @return string|int|bool the ID of the created meta entry if it didn't exist, or `true` if it did exist
     * @throws CustomPostMetaCRUDMutationException If there was an error (eg: taxonomy term does not exist)
     */
    public function updateTermMeta(
        string|int $customPostID,
        string $key,
        mixed $value,
        mixed $prevValue = null,
    ): string|int|bool {
        return $this->updateCustomPostMeta(
            $customPostID,
            $key,
            $value,
            $prevValue,
        );
    }

    /**
     * @throws CustomPostMetaCRUDMutationException If there was an error (eg: taxonomy does not exist)
     */
    public function deleteTermMeta(
        string|int $customPostID,
        string $key,
    ): void {
        $this->deleteCustomPostMeta(
            $customPostID,
            $key,
        );
    }
}
