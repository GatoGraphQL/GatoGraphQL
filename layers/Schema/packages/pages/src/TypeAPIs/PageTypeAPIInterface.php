<?php

declare(strict_types=1);

namespace PoPSchema\Pages\TypeAPIs;

use PoPSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;

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
    /**
     * Get the list of pages
     */
    public function getPages(array $query, array $options = []): array;
    /**
     * Get the number of pages
     */
    public function getPageCount(array $query = [], array $options = []): int;
    /**
     * Page custom post type
     */
    public function getPageCustomPostType(): string;

    public function getPageId(object $page): string | int;
}
