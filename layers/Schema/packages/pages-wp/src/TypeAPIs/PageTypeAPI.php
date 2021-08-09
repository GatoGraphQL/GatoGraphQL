<?php

declare(strict_types=1);

namespace PoPSchema\PagesWP\TypeAPIs;

use WP_Post;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoPSchema\Pages\ComponentConfiguration;
use PoPSchema\Pages\TypeAPIs\PageTypeAPIInterface;
use PoPSchema\CustomPostsWP\TypeAPIs\CustomPostTypeAPI;

use function get_post;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class PageTypeAPI extends CustomPostTypeAPI implements PageTypeAPIInterface
{
    public const HOOK_QUERY = __CLASS__ . ':query';

    /**
     * Add an extra hook just to modify pages
     *
     * @param array<string, mixed> $query
     * @param array<string, mixed> $options
     * @return array<string, mixed>
     */
    protected function convertCustomPostsQuery(array $query, array $options = []): array
    {
        $query = parent::convertCustomPostsQuery($query, $options);
        return HooksAPIFacade::getInstance()->applyFilters(
            self::HOOK_QUERY,
            $query,
            $options
        );
    }

    /**
     * Indicates if the passed object is of type Page
     */
    public function isInstanceOfPageType(object $object): bool
    {
        return ($object instanceof WP_Post) && $object->post_type == 'page';
    }

    /**
     * Get the page with provided ID or, if it doesn't exist, null
     */
    public function getPage(int | string $id): ?object
    {
        $page = get_post($id);
        if (!$page || $page->post_type != 'page') {
            return null;
        }
        return $page;
    }

    /**
     * Indicate if an page with provided ID exists
     */
    public function pageExists(int | string $id): bool
    {
        return $this->getPage($id) != null;
    }

    /**
     * Limit of how many custom posts can be retrieved in the query.
     * Override this value for specific custom post types
     */
    protected function getCustomPostListMaxLimit(): int
    {
        return ComponentConfiguration::getPageListMaxLimit();
    }

    public function getPages(array $query, array $options = []): array
    {
        $query['custompost-types'] = ['page'];
        return $this->getCustomPosts($query, $options);
    }
    public function getPageCount(array $query = [], array $options = []): int
    {
        $query['custompost-types'] = ['page'];
        return $this->getCustomPostCount($query, $options);
    }
    public function getPageCustomPostType(): string
    {
        return 'page';
    }

    public function getPageId(object $page): string | int
    {
        return $page->ID;
    }
}
