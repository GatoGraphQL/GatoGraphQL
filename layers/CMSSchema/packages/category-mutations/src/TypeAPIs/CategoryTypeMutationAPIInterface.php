<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\TypeAPIs;

use PoPCMSSchema\CategoryMutations\Exception\CategoryTermCRUDMutationException;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface CategoryTypeMutationAPIInterface
{
    /**
     * @param array<string,mixed> $data
     * @return string|int the ID of the created category
     * @throws CategoryTermCRUDMutationException If there was an error (eg: some Custom Post creation validation failed)
     */
    public function createCategory(array $data): string|int;
    /**
     * @param array<string,mixed> $data
     * @return string|int the ID of the updated category
     * @throws CategoryTermCRUDMutationException If there was an error (eg: Custom Post does not exist)
     */
    public function updateCategory(array $data): string|int;
    public function canUserEditCategory(string|int $userID, string|int $categoryID): bool;
}
