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
     *
     * @param [type] $object
     * @return boolean
     */
    public function isInstanceOfPageType($object): bool;
    /**
     * Indicate if an page with provided ID exists
     *
     * @param [type] $id
     * @return void
     */
    public function pageExists($id): bool;
    /**
     * Get the page with provided ID or, if it doesn't exist, null
     *
     * @param int $id
     * @return void
     */
    public function getPage($id);
    /**
     * Get the list of pages
     *
     * @param array $query
     * @param array $options
     * @return array
     */
    public function getPages(array $query, array $options = []): array;
    /**
     * Get the number of pages
     *
     * @param array $query
     * @param array $options
     * @return array
     */
    public function getPageCount(array $query = [], array $options = []): int;
    /**
     * Page custom post type
     *
     * @return string
     */
    public function getPageCustomPostType(): string;
    /**
     * Get the ID of the static page for the homepage
     * Returns an ID (int? string?) or null
     *
     * @return null|ID
     */
    public function getHomeStaticPageID();
}
