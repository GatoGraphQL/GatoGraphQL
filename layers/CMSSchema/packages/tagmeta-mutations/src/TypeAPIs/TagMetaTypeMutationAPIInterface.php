<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMetaMutations\TypeAPIs;

use PoPCMSSchema\TagMetaMutations\Exception\TagTermMetaCRUDMutationException;
use PoPCMSSchema\TaxonomyMetaMutations\TypeAPIs\TaxonomyMetaTypeMutationAPIInterface;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface TagMetaTypeMutationAPIInterface extends TaxonomyMetaTypeMutationAPIInterface
{
    /**
     * @param array<string,mixed[]|null> $entries
     * @throws TagTermMetaCRUDMutationException If there was an error
     */
    public function setTagTermMeta(
        string|int $taxonomyTermID,
        array $entries,
    ): void;

    /**
     * @return int The term_id of the newly created term
     * @throws TagTermMetaCRUDMutationException If there was an error
     */
    public function addTagTermMeta(
        string|int $taxonomyTermID,
        string $key,
        mixed $value,
        bool $single = false,
    ): int;

    /**
     * @return string|int|bool the ID of the created meta entry if it didn't exist, or `true` if it did exist
     * @throws TagTermMetaCRUDMutationException If there was an error (eg: taxonomy term does not exist)
     */
    public function updateTagTermMeta(
        string|int $taxonomyTermID,
        string $key,
        mixed $value,
    ): string|int|bool;

    public function deleteTagTermMeta(
        string|int $taxonomyTermID,
        string $key,
        mixed $value = null,
    ): void;
}
