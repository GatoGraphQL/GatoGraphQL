<?php

declare(strict_types=1);

namespace PoPCMSSchema\Pages\TypeAPIs;

use PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface PageTypeAPIInterface extends CustomPostTypeAPIInterface
{
    /**
     * Indicates if the passed object is of type Page
     */
    public function isInstanceOfPageType(object $object): bool;
    /**
     * Indicate if an page with provided ID exists
     */
    public function pageExists(int | string $id): bool;
    /**
     * Get the page with provided ID or, if it doesn't exist, null
     */
    public function getPage(int | string $id): ?object;
    public function getParentPage(int | string | object $pageObjectOrID): ?object;
    public function getParentPageID(int | string | object $pageObjectOrID): int | string | null;
    /**
     * Get the list of pages.
     * If param "status" in $query is not passed, it defaults to "publish"
     */
    public function getPages(array $query, array $options = []): array;
    /**
     * Get the number of pages.
     * If param "status" in $query is not passed, it defaults to "publish"
     */
    public function getPageCount(array $query = [], array $options = []): int;
    /**
     * Page custom post type
     */
    public function getPageCustomPostType(): string;

    public function getPageId(object $page): string | int;
}
