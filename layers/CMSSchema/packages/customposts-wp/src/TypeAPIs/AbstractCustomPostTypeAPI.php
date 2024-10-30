<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostsWP\TypeAPIs;

use PoPCMSSchema\CustomPostsWP\Constants\QueryOptions;
use PoPCMSSchema\CustomPosts\Constants\CustomPostOrderBy;
use PoPCMSSchema\CustomPosts\Enums\CustomPostStatus;
use PoPCMSSchema\CustomPosts\Module;
use PoPCMSSchema\CustomPosts\ModuleConfiguration;
use PoPCMSSchema\CustomPosts\TypeAPIs\AbstractCustomPostTypeAPI as UpstreamAbstractCustomPostTypeAPI;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\SchemaCommons\Constants\QueryOptions as SchemaCommonsQueryOptions;
use PoP\Root\App;
use WP_Post;

use function esc_sql;
use function get_permalink;
use function get_post;
use function get_post_status;
use function get_post_types;
use function get_posts;
use function get_sample_permalink;
use function get_the_excerpt;
use function get_the_title;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
abstract class AbstractCustomPostTypeAPI extends UpstreamAbstractCustomPostTypeAPI
{
    public const HOOK_QUERY = __CLASS__ . ':query';
    public final const HOOK_ORDERBY_QUERY_ARG_VALUE = __CLASS__ . ':orderby-query-arg-value';
    public final const HOOK_STATUS_QUERY_ARG_VALUE = __CLASS__ . ':status-query-arg-value';

    /**
     * Indicates if the passed object is of type (Generic)CustomPost
     */
    public function isInstanceOfCustomPostType(object $object): bool
    {
        return $object instanceof WP_Post;
    }

    /**
     * Indicate if an post with provided ID exists
     */
    public function customPostExists(int|string $id): bool
    {
        return $this->getCustomPost($id) !== null;
    }

    /**
     * Return the post's ID
     */
    public function getID(object $customPost): string|int
    {
        /** @var WP_Post $customPost */
        return $customPost->ID;
    }

    public function getStatus(string|int|object $customPostObjectOrID): ?string
    {
        $customPostID = $this->getCustomPostID($customPostObjectOrID);
        $status = get_post_status($customPostID);
        if ($status === false) {
            return null;
        }
        return $status;
    }

    /**
     * If the "status" is not passed, then it's always "publish"
     *
     * @return array<string,mixed>
     */
    public function getCustomPostQueryDefaults(): array
    {
        return [
            'status' => [
                CustomPostStatus::PUBLISH,
            ],
        ];
    }

    /**
     * Query args that must always be in the query
     *
     * @return array<string,mixed>
     */
    public function getCustomPostQueryRequiredArgs(): array
    {
        return [];
    }

    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     * @return array<string|int>|object[]
     */
    public function getCustomPosts(array $query, array $options = []): array
    {
        $query = $this->convertCustomPostsQuery($query, $options);

        // If passing an empty array to `filter.ids`, return no results
        if ($this->isFilteringByEmptyArray($query)) {
            return [];
        }

        return get_posts($query);
    }

    /**
     * Indicate if an empty array was passed to `filter.ids`
     *
     * @param array<string,mixed> $query
     */
    protected function isFilteringByEmptyArray(array $query): bool
    {
        return isset($query['include']) && ($query['include'] === '' || $query['include'] === []);
    }

    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getCustomPostCount(array $query, array $options = []): int
    {
        // Convert parameters
        $options[SchemaCommonsQueryOptions::RETURN_TYPE] = ReturnTypes::IDS;
        $query = $this->convertCustomPostsQuery($query, $options);

        // If passing an empty array to `filter.ids`, return no results
        if ($this->isFilteringByEmptyArray($query)) {
            return 0;
        }

        // All results, no offset
        $query['posts_per_page'] = -1;
        unset($query['offset']);

        // Execute query and count results
        $posts = get_posts($query);
        return count($posts);
    }
    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     * @return array<string,mixed>
     */
    protected function convertCustomPostsQuery(array $query, array $options = []): array
    {
        if (($options[SchemaCommonsQueryOptions::RETURN_TYPE] ?? null) === ReturnTypes::IDS) {
            $query['fields'] = 'ids';
        }

        // The query overrides the defaults, and is overridden by the required args
        $query = [
            ...$this->getCustomPostQueryDefaults(),
            ...$query,
            ...$this->getCustomPostQueryRequiredArgs(),
        ];

        // Convert the parameters
        if (isset($query['status'])) {
            /**
             * This can be both an array and a single value
             *
             * @var string|string[]
             */
            $status = $query['status'];
            unset($query['status']);
            /**
             * @todo "auto-draft" must be converted to enum value "auto_draft" on `Post.status`.
             *       Until then, this code is commented
             *
             * The status may need to be converted to some underlying value
             */
            // $query['post_status'] = is_array($status)
            //     ? array_map(
            //         $this->getStatusQueryArgValue(...),
            //         $status
            //     )
            //     : $this->getStatusQueryArgValue($status);
            $query['post_status'] = $status;
        }
        if (isset($query['include']) && is_array($query['include'])) {
            // It can be an array or a string
            $query['include'] = implode(',', $query['include']);
        }
        if (isset($query['exclude-ids'])) {
            $query['post__not_in'] = $query['exclude-ids'];
            unset($query['exclude-ids']);
        }
        /**
         * If querying "customPostCount(postTypes:[])" it would reset
         * the list to only "post". So check that customPostTypes is
         * not empty.
         */
        if (isset($query['custompost-types']) && !empty($query['custompost-types'])) {
            $query['post_type'] = $query['custompost-types'];
            unset($query['custompost-types']);
        } else {
            /**
             * If not adding the custom post types, WordPress only uses "post",
             * so querying by CPT would fail loading data.
             *
             * There are 2 possibilities for loading private data:
             *
             *   - 1. By Gato GraphQL to load its own data ("ForPluginOwnUse"), via the query options
             *   - 2. By a standalone plugin that does not expose public endpoints, via customizing module configuration
             */
            /** @var ModuleConfiguration */
            $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
            $allowQueryingPrivateCPTs = $options[QueryOptions::ALLOW_QUERYING_PRIVATE_CPTS] ?? $moduleConfiguration->allowQueryingPrivateCPTs();
            $customPostTypeOptions = $allowQueryingPrivateCPTs ? [] : ['publicly-queryable' => true];
            $query['post_type'] = $this->getCustomPostTypes($customPostTypeOptions);
        }
        // Querying "attachment" doesn't work in an array!
        if (is_array($query['post_type']) && count($query['post_type']) === 1) {
            $query['post_type'] = $query['post_type'][0];
        }
        if (isset($query['offset'])) {
            // Same param name, so do nothing
        }
        if (isset($query['limit'])) {
            $limit = (int) $query['limit'];
            $query['posts_per_page'] = $limit;
            unset($query['limit']);
        }
        if (isset($query['order'])) {
            $query['order'] = esc_sql($query['order']);
        }
        if (isset($query['orderby'])) {
            // Maybe replace the provided value
            $query['orderby'] = esc_sql($this->getOrderByQueryArgValue($query['orderby']));
        }
        // Post slug
        if (isset($query['slug'])) {
            $query['name'] = $query['slug'];
            unset($query['slug']);
        }
        if (isset($query['post-not-in'])) {
            $query['post__not_in'] = $query['post-not-in'];
            unset($query['post-not-in']);
        }
        if (isset($query['search'])) {
            $query['is_search'] = true;
            $query['s'] = $query['search'];
            unset($query['search']);
        }
        // Filtering by date: Instead of operating on the query, it does it through filter 'posts_where'
        if (isset($query['date-from'])) {
            $query['date_query'][] = [
                'after' => $query['date-from'],
                'inclusive' => false,
            ];
            unset($query['date-from']);
        }
        if (isset($query['date-to'])) {
            $query['date_query'][] = [
                'before' => $query['date-to'],
                'inclusive' => false,
            ];
            unset($query['date-to']);
        }

        return App::applyFilters(
            self::HOOK_QUERY,
            $query,
            $options
        );
    }

    /**
     * @todo "auto-draft" must be converted to enum value "auto_draft" on `Post.status`.
     *       Until then, this code is commented
     *
     * Allow "auto_draft" to be converted to "auto-draft"
     */
    // protected function getStatusQueryArgValue(string $status): string
    // {
    //     return App::applyFilters(
    //         self::HOOK_STATUS_QUERY_ARG_VALUE,
    //         $status
    //     );
    // }
    protected function getOrderByQueryArgValue(string $orderBy): string
    {
        $orderBy = match ($orderBy) {
            CustomPostOrderBy::ID => 'ID',
            CustomPostOrderBy::TITLE => 'title',
            CustomPostOrderBy::DATE => 'date',
            default => $orderBy,
        };
        return App::applyFilters(
            self::HOOK_ORDERBY_QUERY_ARG_VALUE,
            $orderBy
        );
    }
    /**
     * @return string[]
     * @param array<string,mixed> $query
     */
    public function getCustomPostTypes(array $query = array()): array
    {
        // Convert the parameters
        if (isset($query['exclude-from-search'])) {
            $query['exclude_from_search'] = $query['exclude-from-search'];
            unset($query['exclude-from-search']);
        }
        if (isset($query['publicly-queryable'])) {
            $query['publicly_queryable'] = $query['publicly-queryable'];
            unset($query['publicly-queryable']);
        }
        /** @var string[] */
        return get_post_types($query);
    }

    public function getPermalink(string|int|object $customPostObjectOrID): ?string
    {
        $customPostID = $this->getCustomPostID($customPostObjectOrID);
        if ($this->getStatus($customPostObjectOrID) === CustomPostStatus::PUBLISH) {
            $permalink = get_permalink($customPostID);
            if ($permalink === false) {
                return null;
            }
            return $permalink;
        }

        // Function get_sample_permalink comes from the file below, so it must be included
        // Code below copied from `function get_sample_permalink_html`
        include_once ABSPATH . 'wp-admin/includes/post.php';
        list($permalink, $post_name) = get_sample_permalink($customPostID, null, null);
        return str_replace(['%pagename%', '%postname%'], $post_name, $permalink);
    }


    public function getSlug(string|int|object $customPostObjectOrID): ?string
    {
        list(
            $customPost,
            $customPostID,
        ) = $this->getCustomPostObjectAndID($customPostObjectOrID);
        if ($customPost === null) {
            return null;
        }
        /** @var WP_Post $customPost */
        if ($this->getStatus($customPostObjectOrID) === CustomPostStatus::PUBLISH) {
            return $customPost->post_name;
        }

        // Function get_sample_permalink comes from the file below, so it must be included
        // Code below copied from `function get_sample_permalink_html`
        include_once ABSPATH . 'wp-admin/includes/post.php';
        list($permalink, $post_name) = get_sample_permalink((int)$customPostID, null, null);
        return $post_name;
    }

    public function getExcerpt(string|int|object $customPostObjectOrID): ?string
    {
        /** @var WP_Post|null */
        $customPost = $this->getCustomPostObject($customPostObjectOrID);
        if ($customPost === null) {
            return null;
        }
        return get_the_excerpt($customPost);
    }

    public function getRawExcerpt(string|int|object $customPostObjectOrID): ?string
    {
        /** @var WP_Post|null */
        $customPost = $this->getCustomPostObject($customPostObjectOrID);
        if ($customPost === null) {
            return null;
        }
        return $customPost->post_excerpt;
    }

    /**
     * @return array{0:WP_Post|null,1:null|string|int}
     */
    protected function getCustomPostObjectAndID(string|int|object $customPostObjectOrID): array
    {
        if (is_object($customPostObjectOrID)) {
            /** @var WP_Post */
            $customPost = $customPostObjectOrID;
            $customPostID = $customPost->ID;
        } else {
            $customPostID = $customPostObjectOrID;
            /** @var WP_Post|null */
            $customPost = \get_post((int)$customPostID);
        }
        return [
            $customPost,
            $customPostID,
        ];
    }

    protected function getCustomPostObject(string|int|object $customPostObjectOrID): ?object
    {
        if (is_object($customPostObjectOrID)) {
            return $customPostObjectOrID;
        }
        /** @var string|int */
        $customPostID = $customPostObjectOrID;
        return \get_post((int)$customPostID);
    }

    protected function getCustomPostID(string|int|object $customPostObjectOrID): int
    {
        if (is_object($customPostObjectOrID)) {
            /** @var WP_Post */
            $customPost = $customPostObjectOrID;
            return $customPost->ID;
        }
        return (int)$customPostObjectOrID;
    }

    public function getTitle(string|int|object $customPostObjectOrID): ?string
    {
        /** @var WP_Post|null */
        $customPost = $this->getCustomPostObject($customPostObjectOrID);
        if ($customPost === null) {
            return null;
        }
        /** @var WP_Post $customPost */
        return get_the_title($customPost);
    }

    public function getRawTitle(string|int|object $customPostObjectOrID): ?string
    {
        /** @var WP_Post|null */
        $customPost = $this->getCustomPostObject($customPostObjectOrID);
        if ($customPost === null) {
            return null;
        }
        /** @var WP_Post $customPost */
        return $customPost->post_title;
    }

    public function getContent(string|int|object $customPostObjectOrID): ?string
    {
        /** @var WP_Post|null */
        $customPost = $this->getCustomPostObject($customPostObjectOrID);
        if ($customPost === null) {
            return null;
        }
        return \apply_filters('the_content', $customPost->post_content);
    }

    public function getRawContent(string|int|object $customPostObjectOrID): ?string
    {
        /** @var WP_Post|null */
        $customPost = $this->getCustomPostObject($customPostObjectOrID);
        if ($customPost === null) {
            return null;
        }

        return $customPost->post_content;
    }

    public function getPublishedDate(string|int|object $customPostObjectOrID, bool $gmt = false): ?string
    {
        /** @var WP_Post|null */
        $customPost = $this->getCustomPostObject($customPostObjectOrID);
        if ($customPost === null) {
            return null;
        }
        return $gmt ? $customPost->post_date_gmt : $customPost->post_date;
    }

    public function getModifiedDate(string|int|object $customPostObjectOrID, bool $gmt = false): ?string
    {
        /** @var WP_Post|null */
        $customPost = $this->getCustomPostObject($customPostObjectOrID);
        if ($customPost === null) {
            return null;
        }
        return $gmt ? $customPost->post_modified_gmt : $customPost->post_modified;
    }
    public function getCustomPostType(string|int|object $customPostObjectOrID): ?string
    {
        /** @var WP_Post|null */
        $customPost = $this->getCustomPostObject($customPostObjectOrID);
        return $customPost?->post_type;
    }

    /**
     * Get the post with provided ID or, if it doesn't exist, null
     */
    public function getCustomPost(int|string $id): ?object
    {
        /** @var object|null */
        return get_post((int)$id);
    }
}
