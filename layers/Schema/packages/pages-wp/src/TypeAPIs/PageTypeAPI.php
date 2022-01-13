<?php

declare(strict_types=1);

namespace PoPSchema\PagesWP\TypeAPIs;

use PoP\Root\App;
use PoP\ComponentModel\Component as ComponentModelComponent;
use PoP\ComponentModel\ComponentConfiguration as ComponentModelComponentConfiguration;
use PoPSchema\CustomPostsWP\TypeAPIs\AbstractCustomPostTypeAPI;
use PoPSchema\Pages\Component;
use PoPSchema\Pages\ComponentConfiguration;
use PoPSchema\Pages\TypeAPIs\PageTypeAPIInterface;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use WP_Post;

use function get_post;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class PageTypeAPI extends AbstractCustomPostTypeAPI implements PageTypeAPIInterface
{
    public const HOOK_QUERY = __CLASS__ . ':query';

    /**
     * @param array<string, mixed> $query
     * @param array<string, mixed> $options
     * @return array<string, mixed>
     */
    protected function convertCustomPostsQuery(array $query, array $options = []): array
    {
        $query = parent::convertCustomPostsQuery($query, $options);

        $query = $this->convertPagesQuery($query, $options);

        return \PoP\Root\App::getHookManager()->applyFilters(
            self::HOOK_QUERY,
            $query,
            $options
        );
    }

    /**
     * Query args that must always be in the query
     *
     * @return array<string, mixed>
     */
    public function getCustomPostQueryRequiredArgs(): array
    {
        return array_merge(
            parent::getCustomPostQueryRequiredArgs(),
            [
                'custompost-types' => ['page'],
            ]
        );
    }

    /**
     * @param array<string, mixed> $query
     * @param array<string, mixed> $options
     * @return array<string, mixed>
     */
    protected function convertPagesQuery(array $query, array $options = []): array
    {
        // A page can have an ancestor
        if (isset($query['parent-id'])) {
            $query['post_parent'] = $query['parent-id'];
            unset($query['parent-id']);
        }
        if (isset($query['parent-ids'])) {
            $query['post_parent__in'] = $query['parent-ids'];
            unset($query['parent-ids']);
        }
        if (isset($query['exclude-parent-ids'])) {
            $query['post_parent__not_in'] = $query['exclude-parent-ids'];
            unset($query['exclude-parent-ids']);
        }

        return $query;
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

    public function getParentPage(int | string | object $pageObjectOrID): ?object
    {
        $pageParentID = $this->getParentPageID($pageObjectOrID);
        if ($pageParentID === null) {
            return null;
        }
        return $this->getPage($pageParentID);
    }

    public function getParentPageID(int | string | object $pageObjectOrID): int | string | null
    {
        /** @var WP_Post $page */
        list(
            $page,
            $pageID,
        ) = $this->getCustomPostObjectAndID($pageObjectOrID);

        $pageParentID = $page->post_parent;
        if ($pageParentID === 0) {
            return null;
        }
        return $pageParentID;
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
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        return $componentConfiguration->getPageListMaxLimit();
    }

    public function getPages(array $query, array $options = []): array
    {
        /**
         * "paths" is unique to pages
         */
        if ($paths = $query['paths'] ?? []) {
            $returnIDs = ($options[QueryOptions::RETURN_TYPE] ?? null) === ReturnTypes::IDS;
            $pageIDs = [];
            /** @var ComponentModelComponentConfiguration */
            $componentConfiguration = App::getComponent(ComponentModelComponent::class)->getConfiguration();
            $enableAdminSchema = $componentConfiguration->enableAdminSchema();
            foreach ($paths as $path) {
                /** @var WP_Post|null */
                $page = \get_page_by_path($path);
                if ($page === null) {
                    continue;
                }
                // If the "admin" schema is not enabled, Only expose posts with status "publish"
                if (!$enableAdminSchema && $page->post_status !== "publish") {
                    continue;
                }
                $pageIDs[] = $returnIDs ? $page->ID : $page;
            }
            return $pageIDs;
        }
        return $this->getCustomPosts($query, $options);
    }
    public function getPageCount(array $query = [], array $options = []): int
    {
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
