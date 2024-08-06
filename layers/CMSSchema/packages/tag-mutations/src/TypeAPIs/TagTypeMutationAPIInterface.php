<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMutations\TypeAPIs;

use PoPCMSSchema\TagMutations\Exception\TagTermCRUDMutationException;
use PoPCMSSchema\TaxonomyMutations\TypeAPIs\TaxonomyTypeMutationAPIInterface;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface TagTypeMutationAPIInterface extends TaxonomyTypeMutationAPIInterface
{
    /**
     * @param array<string,mixed> $data
     * @return string|int the ID of the created tag
     * @throws TagTermCRUDMutationException If there was an error (eg: some taxonomy term creation validation failed)
     */
    public function createTagTerm(string $taxonomyName, array $data): string|int;
    /**
     * @param array<string,mixed> $data
     * @return string|int the ID of the updated tag
     * @throws TagTermCRUDMutationException If there was an error (eg: taxonomy term does not exist)
     */
    public function updateTagTerm(string|int $taxonomyTermID, string $taxonomyName, array $data): string|int;
    public function deleteTagTerm(string|int $taxonomyTermID, string $taxonomyName): bool;
}
