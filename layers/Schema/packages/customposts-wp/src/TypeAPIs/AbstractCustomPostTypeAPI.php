<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostsWP\TypeAPIs;

use PoPSchema\CustomPosts\Component;
use PoPSchema\CustomPosts\ComponentConfiguration;
use PoPSchema\CustomPosts\Constants\CustomPostOrderBy;
use PoPSchema\CustomPosts\TypeAPIs\AbstractCustomPostTypeAPI as UpstreamAbstractCustomPostTypeAPI;
use PoPSchema\CustomPosts\Enums\CustomPostStatus;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use WP_Post;

use function get_post_status;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
abstract class AbstractCustomPostTypeAPI extends UpstreamAbstractCustomPostTypeAPI
{
    public const HOOK_QUERY = __CLASS__ . ':query';
    public const HOOK_ORDERBY_QUERY_ARG_VALUE = __CLASS__ . ':orderby-query-arg-value';

    /**
     * Return the post's ID
     */
    public function getID(object $customPost): string | int
    {
        return $customPost->ID;
    }

    public function getStatus(string | int | object $customPostObjectOrID): ?string
    {
        return get_post_status($customPostObjectOrID);
    }

    /**
     * If the "status" is not passed, then it's always "publish"
     *
     * @return array<string, mixed>
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
     * @return array<string, mixed>
     */
    public function getCustomPostQueryRequiredArgs(): array
    {
        return [];
    }

    /**
     * @param array<string, mixed> $query
     * @param array<string, mixed> $options
     * @return object[]
     */
    public function getCustomPosts(array $query, array $options = []): array
    {
        $query = $this->convertCustomPostsQuery($query, $options);
        return (array) \get_posts($query);
    }
    public function getCustomPostCount(array $query = [], array $options = []): int
    {
        // Convert parameters
        $options[QueryOptions::RETURN_TYPE] = ReturnTypes::IDS;
        $query = $this->convertCustomPostsQuery($query, $options);

        // All results, no offset
        $query['posts_per_page'] = -1;
        unset($query['offset']);

        // Execute query and count results
        $posts = \get_posts($query);
        return count($posts);
    }
    /**
     * Limit of how many custom posts can be retrieved in the query.
     * Override this value for specific custom post types
     */
    protected function getCustomPostListMaxLimit(): int
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = \PoP\Root\Managers\ComponentManager::getComponent(Component::class)->getConfiguration();
        return $componentConfiguration->getCustomPostListMaxLimit();
    }
    /**
     * @param array<string, mixed> $query
     * @param array<string, mixed> $options
     * @return array<string, mixed>
     */
    protected function convertCustomPostsQuery(array $query, array $options = []): array
    {
        if (($options[QueryOptions::RETURN_TYPE] ?? null) === ReturnTypes::IDS) {
            $query['fields'] = 'ids';
        }

        // The query overrides the defaults, and is overriden by the required args
        $query = array_merge(
            $this->getCustomPostQueryDefaults(),
            $query,
            $this->getCustomPostQueryRequiredArgs(),
        );

        // Convert the parameters
        if (isset($query['status'])) {
            // This can be both an array and a single value
            $query['post_status'] = $query['status'];
            unset($query['status']);
        }
        if (isset($query['include']) && is_array($query['include'])) {
            // It can be an array or a string
            $query['include'] = implode(',', $query['include']);
        }
        if (isset($query['exclude-ids'])) {
            $query['post__not_in'] = $query['exclude-ids'];
            unset($query['exclude-ids']);
        }
        // If querying "customPostCount(postTypes:[])" it would reset the list to only "post"
        // So check that postTypes is not empty
        if (isset($query['custompost-types']) && !empty($query['custompost-types'])) {
            $query['post_type'] = $query['custompost-types'];
            unset($query['custompost-types']);
        } else {
            // If not adding the post types, WordPress only uses "post", so querying by CPT would fail loading data
            $query['post_type'] = $this->getCustomPostTypes([
                'publicly-queryable' => true,
            ]);
        }
        // Querying "attachment" doesn't work in an array!
        if (isset($query['post_type']) && is_array($query['post_type']) && count($query['post_type']) === 1) {
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
            $query['order'] = \esc_sql($query['order']);
        }
        if (isset($query['orderby'])) {
            // Maybe replace the provided value
            $query['orderby'] = \esc_sql($this->getOrderByQueryArgValue($query['orderby']));
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

        return $this->getHooksAPI()->applyFilters(
            self::HOOK_QUERY,
            $query,
            $options
        );
    }
    protected function getOrderByQueryArgValue(string $orderBy): string
    {
        $orderBy = match ($orderBy) {
            CustomPostOrderBy::ID => 'ID',
            CustomPostOrderBy::TITLE => 'title',
            CustomPostOrderBy::DATE => 'date',
            default => $orderBy,
        };
        return $this->getHooksAPI()->applyFilters(
            self::HOOK_ORDERBY_QUERY_ARG_VALUE,
            $orderBy
        );
    }
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
        return \get_post_types($query);
    }

    public function getPermalink(string | int | object $customPostObjectOrID): ?string
    {
        $customPostID = $this->getCustomPostID($customPostObjectOrID);
        if ($customPostID === null) {
            return null;
        }
        if ($this->getStatus($customPostObjectOrID) == CustomPostStatus::PUBLISH) {
            return \get_permalink($customPostID);
        }

        // Function get_sample_permalink comes from the file below, so it must be included
        // Code below copied from `function get_sample_permalink_html`
        include_once ABSPATH . 'wp-admin/includes/post.php';
        list($permalink, $post_name) = \get_sample_permalink($customPostID, null, null);
        return str_replace(['%pagename%', '%postname%'], $post_name, $permalink);
    }


    public function getSlug(string | int | object $customPostObjectOrID): ?string
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
        list($permalink, $post_name) = \get_sample_permalink($customPostID, null, null);
        return $post_name;
    }

    public function getExcerpt(string | int | object $customPostObjectOrID): ?string
    {
        return \get_the_excerpt($customPostObjectOrID);
    }
    /**
     * @return mixed[]
     */
    protected function getCustomPostObjectAndID(string | int | object $customPostObjectOrID): array
    {
        return CustomPostTypeAPIHelpers::getCustomPostObjectAndID($customPostObjectOrID);
    }

    protected function getCustomPostObject(string | int | object $customPostObjectOrID): ?object
    {
        list(
            $customPost,
            $customPostID,
        ) = $this->getCustomPostObjectAndID($customPostObjectOrID);
        return $customPost;
    }

    protected function getCustomPostID(string | int | object $customPostObjectOrID): ?int
    {
        list(
            $customPost,
            $customPostID,
        ) = $this->getCustomPostObjectAndID($customPostObjectOrID);
        return $customPostID;
    }

    public function getTitle(string | int | object $customPostObjectOrID): ?string
    {
        list(
            $customPost,
            $customPostID,
        ) = $this->getCustomPostObjectAndID($customPostObjectOrID);
        if ($customPost === null) {
            return null;
        }
        /** @var WP_Post $customPost */
        return $this->getHooksAPI()->applyFilters('the_title', $customPost->post_title, $customPostID);
    }

    public function getContent(string | int | object $customPostObjectOrID): ?string
    {
        $customPost = $this->getCustomPostObject($customPostObjectOrID);
        if ($customPost === null) {
            return null;
        }
        return $this->getHooksAPI()->applyFilters('the_content', $customPost->post_content);
    }

    public function getRawContent(string | int | object $customPostObjectOrID): ?string
    {
        $customPost = $this->getCustomPostObject($customPostObjectOrID);
        if ($customPost === null) {
            return null;
        }

        // Basic content: remove embeds, shortcodes, and tags
        // Remove unneeded filters, then add them again
        // @see wp-includes/default-filters.php
        $wp_embed = $GLOBALS['wp_embed'];
        $this->getHooksAPI()->removeFilter('the_content', array( $wp_embed, 'autoembed' ), 8);
        $this->getHooksAPI()->removeFilter('the_content', 'wpautop');

        // Do not allow HTML tags or shortcodes
        $ret = \strip_shortcodes($customPost->post_content);
        $ret = $this->getHooksAPI()->applyFilters('the_content', $ret);
        $this->getHooksAPI()->addFilter('the_content', array( $wp_embed, 'autoembed' ), 8);
        $this->getHooksAPI()->addFilter('the_content', 'wpautop');

        return strip_tags($ret);
    }

    public function getPublishedDate(string | int | object $customPostObjectOrID, bool $gmt = false): ?string
    {
        $customPost = $this->getCustomPostObject($customPostObjectOrID);
        if ($customPost === null) {
            return null;
        }
        return $gmt ? $customPost->post_date_gmt : $customPost->post_date;
    }

    public function getModifiedDate(string | int | object $customPostObjectOrID, bool $gmt = false): ?string
    {
        $customPost = $this->getCustomPostObject($customPostObjectOrID);
        if ($customPost === null) {
            return null;
        }
        return $gmt ? $customPost->post_modified_gmt : $customPost->post_modified;
    }
    public function getCustomPostType(string | int | object $customPostObjectOrID): string
    {
        $customPost = $this->getCustomPostObject($customPostObjectOrID);
        return $customPost?->post_type;
    }

    /**
     * Get the post with provided ID or, if it doesn't exist, null
     */
    public function getCustomPost(int | string $id): ?object
    {
        return \get_post($id);
    }
}
