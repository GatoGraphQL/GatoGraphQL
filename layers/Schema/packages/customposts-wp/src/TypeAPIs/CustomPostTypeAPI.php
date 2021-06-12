<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostsWP\TypeAPIs;

use function apply_filters;
use function get_post_status;
use PoP\ComponentModel\TypeDataResolvers\InjectedFilterDataloadingModuleTypeDataResolverTrait;
use PoP\Hooks\HooksAPIInterface;
use PoPSchema\CustomPosts\ComponentConfiguration;
use PoPSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoPSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;
use PoPSchema\CustomPosts\Types\Status;
use PoPSchema\CustomPostsWP\TypeAPIs\CustomPostTypeAPIHelpers;
use PoPSchema\CustomPostsWP\TypeAPIs\CustomPostTypeAPIUtils;
use PoPSchema\QueriedObject\Helpers\QueriedObjectHelperServiceInterface;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class CustomPostTypeAPI implements CustomPostTypeAPIInterface
{
    use InjectedFilterDataloadingModuleTypeDataResolverTrait;

    function __construct(
        protected HooksAPIInterface $hooksAPI,
        protected QueriedObjectHelperServiceInterface $queriedObjectHelperService,
    ) {        
    }

    // public const NON_EXISTING_ID = "non-existing";

    /**
     * Return the post's ID
     */
    public function getID(object $customPost): string | int
    {
        return $customPost->ID;
    }

    public function getStatus(string | int | object $customPostObjectOrID): ?string
    {
        $status = get_post_status($customPostObjectOrID);
        return CustomPostTypeAPIUtils::convertPostStatusFromCMSToPoP($status);
    }

    /**
     * @param array<string, mixed> $query
     * @param array<string, mixed> $options
     * @return array<string, mixed>
     */
    public function getCustomPosts(array $query, array $options = []): array
    {
        $query = $this->convertCustomPostsQuery($query, $options);
        return (array) \get_posts($query);
    }
    public function getCustomPostCount(array $query = [], array $options = []): int
    {
        // Convert parameters
        $options['return-type'] = ReturnTypes::IDS;
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
        return ComponentConfiguration::getCustomPostListMaxLimit();
    }
    /**
     * @param array<string, mixed> $query
     * @param array<string, mixed> $options
     * @return array<string, mixed>
     */
    protected function convertCustomPostsQuery(array $query, array $options = []): array
    {
        if ($return_type = $options['return-type'] ?? null) {
            if ($return_type == ReturnTypes::IDS) {
                $query['fields'] = 'ids';
            }
        }

        // Accept field atts to filter the API fields
        $this->maybeFilterDataloadQueryArgs($query, $options);

        // Convert the parameters
        if (isset($query['status'])) {
            if (is_array($query['status'])) {
                // doing get_posts can accept an array of values
                $query['post_status'] = array_map(
                    [CustomPostTypeAPIUtils::class, 'convertPostStatusFromPoPToCMS'],
                    $query['status']
                );
            } else {
                // doing wp_insert/update_post accepts a single value
                $query['post_status'] = CustomPostTypeAPIUtils::convertPostStatusFromPoPToCMS($query['status']);
            }
            unset($query['status']);
        }
        if ($query['include'] ?? null) {
            // Transform from array to string
            $query['include'] = implode(',', $query['include']);

            // Make sure the post can also be draft or pending
            if (!isset($query['post_status'])) {
                $query['post_status'] = CustomPostTypeAPIUtils::getCMSPostStatuses();
            }
        }
        // If querying "customPostCount(postTypes:[])" it would reset the list to only "post"
        // So check that postTypes is not empty
        if (isset($query['custompost-types']) && !empty($query['custompost-types'])) {
            $query['post_type'] = $query['custompost-types'];
            // // Make sure they are public, to avoid an external query requesting forbidden data
            // $postTypes = array_intersect(
            //     $query['custompost-types'],
            //     $this->getCustomPostTypes(['public' => true])
            // );
            // // If there are no valid postTypes, then return no results
            // // By not adding the post type, WordPress will fetch a "post"
            // // Then, include a non-existing ID
            // if ($postTypes) {
            //     $query['post_type'] = $postTypes;
            // } else {
            //     $query['include'] = self::NON_EXISTING_ID; // Non-existing ID
            // }
            unset($query['custompost-types']);
        } elseif ($unionTypeResolverClass = $query['types-from-union-resolver-class'] ?? null) {
            $query['post_type'] = CustomPostUnionTypeHelpers::getTargetTypeResolverCustomPostTypes(
                $unionTypeResolverClass
            );
            unset($query['types-from-union-resolver-class']);
        }
        // else {
        //     // Default value: only get POST, no CPTs
        //     $query['post_type'] = ['post'];
        // }
        if (isset($query['offset'])) {
            // Same param name, so do nothing
        }
        if (isset($query['limit'])) {
            // Maybe restrict the limit, if higher than the max limit
            // Allow to not limit by max when querying from within the application
            $limit = (int) $query['limit'];
            if (!isset($options['skip-max-limit']) || !$options['skip-max-limit']) {
                $limit = $this->queriedObjectHelperService->getLimitOrMaxLimit(
                    $limit,
                    $this->getCustomPostListMaxLimit()
                );
            }

            // Assign the limit as the required attribute
            $query['posts_per_page'] = $limit;
            unset($query['limit']);
        }
        if (isset($query['order'])) {
            // Same param name, so do nothing
        }
        if (isset($query['orderby'])) {
            // Same param name, so do nothing
            // This param can either be a string or an array. Eg:
            // $query['orderby'] => array('date' => 'DESC', 'title' => 'ASC');
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
        if (isset($query['date-from-inclusive'])) {
            $query['date_query'][] = [
                'after' => $query['date-from-inclusive'],
                'inclusive' => true,
            ];
            unset($query['date-from-inclusive']);
        }
        if (isset($query['date-to'])) {
            $query['date_query'][] = [
                'before' => $query['date-to'],
                'inclusive' => false,
            ];
            unset($query['date-to']);
        }
        if (isset($query['date-to-inclusive'])) {
            $query['date_query'][] = [
                'before' => $query['date-to-inclusive'],
                'inclusive' => true,
            ];
            unset($query['date-to-inclusive']);
        }

        return $this->hooksAPI->applyFilters(
            'CMSAPI:customposts:query',
            $query,
            $options
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
        // Same key, so no need to convert
        // if (isset($query['public'])) {
        //     $query['public'] = $query['public'];
        //     unset($query['public']);
        // }
        return \get_post_types($query);
    }

    public function getPermalink(string | int | object $customPostObjectOrID): ?string
    {
        list(
            $customPost,
            $customPostID,
        ) = $this->getCustomPostObjectAndID($customPostObjectOrID);
        if ($this->getStatus($customPostObjectOrID) == Status::PUBLISHED) {
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
        if ($this->getStatus($customPostObjectOrID) == Status::PUBLISHED) {
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
    protected function getCustomPostObjectAndID(string | int | object $customPostObjectOrID): array
    {
        return CustomPostTypeAPIHelpers::getCustomPostObjectAndID($customPostObjectOrID);
    }

    public function getTitle(string | int | object $customPostObjectOrID): ?string
    {
        list(
            $customPost,
            $customPostID,
        ) = $this->getCustomPostObjectAndID($customPostObjectOrID);
        return apply_filters('the_title', $customPost->post_title, $customPostID);
    }

    public function getContent(string | int | object $customPostObjectOrID): ?string
    {
        list(
            $customPost,
            $customPostID,
        ) = $this->getCustomPostObjectAndID($customPostObjectOrID);
        return apply_filters('the_content', $customPost->post_content);
    }

    public function getPlainTextContent(string | int | object $customPostObjectOrID): string
    {
        list(
            $customPost,
            $customPostID,
        ) = $this->getCustomPostObjectAndID($customPostObjectOrID);

        // Basic content: remove embeds, shortcodes, and tags
        // Remove the embed functionality, and then add again
        $wp_embed = $GLOBALS['wp_embed'];
        $this->hooksAPI->removeFilter('the_content', array( $wp_embed, 'autoembed' ), 8);

        // Do not allow HTML tags or shortcodes
        $ret = \strip_shortcodes($customPost->post_content);
        $ret = $this->hooksAPI->applyFilters('the_content', $ret);
        $this->hooksAPI->addFilter('the_content', array( $wp_embed, 'autoembed' ), 8);

        return strip_tags($ret);
    }

    public function getPublishedDate(string | int | object $customPostObjectOrID): ?string
    {
        list(
            $customPost,
            $customPostID,
        ) = $this->getCustomPostObjectAndID($customPostObjectOrID);
        return $customPost->post_date;
    }

    public function getModifiedDate(string | int | object $customPostObjectOrID): ?string
    {
        list(
            $customPost,
            $customPostID,
        ) = $this->getCustomPostObjectAndID($customPostObjectOrID);
        return $customPost->post_modified;
    }
    public function getCustomPostType(string | int | object $customPostObjectOrID): string
    {
        list(
            $customPost,
            $customPostID,
        ) = $this->getCustomPostObjectAndID($customPostObjectOrID);
        return $customPost->post_type;
    }

    /**
     * Get the post with provided ID or, if it doesn't exist, null
     */
    public function getCustomPost(int | string $id): ?object
    {
        return \get_post($id);
    }
}
