<?php
namespace PoP\CMSModel\WP;

class FunctionAPI extends \PoP\CMS\WP\FrontendFunctionAPI implements \PoP\CMSModel\FunctionAPI
{
    use \PoP\CMSModel\FunctionAPI_Trait;

    protected $cmsToPoPPostStatusConversion = [
        'publish' => POP_POSTSTATUS_PUBLISHED,
        'pending' => POP_POSTSTATUS_PENDING,
        'draft' => POP_POSTSTATUS_DRAFT,
        'trash' => POP_POSTSTATUS_TRASH,
    ];
    protected $popToCMSPostStatusConversion;
    protected $cmsToPoPCommentStatusConversion = [
        // 'all' => POP_COMMENTSTATUS_ALL,
        'approve' => POP_COMMENTSTATUS_APPROVED,
        'hold' => POP_COMMENTSTATUS_ONHOLD,
        'spam' => POP_COMMENTSTATUS_SPAM,
        'trash' => POP_COMMENTSTATUS_TRASH,
    ];
    protected $popToCMSCommentStatusConversion;
    
    public function __construct()
    {
        parent::__construct();

        $this->popToCMSPostStatusConversion = array_flip($this->cmsToPoPPostStatusConversion);
        $this->popToCMSCommentStatusConversion = array_flip($this->cmsToPoPCommentStatusConversion);
    }

    // Pages
    public function getHomeStaticPage()
    {
        if (get_option('show_on_front') == 'page') {
            $static_page_id = (int)get_option('page_on_front');
            return $static_page_id > 0 ? $static_page_id : null;
        }

        return null;
    }

    public function getPageIdByPath($page_path)
    {
        $page = get_page_by_path($page_path);
        return $page->ID;
    }


    // Posts
    public function getPostStatus($post_id)
    {
        $status = get_post_status($post_id);
        return $this->convertPostStatusFromCMSToPoP($status);
    }
    protected function convertPostStatusFromCMSToPoP($status)
    {
        // Convert from the CMS status to PoP's one
        return $this->cmsToPoPPostStatusConversion[$status];
    }
    protected function convertPostStatusFromPoPToCMS($status)
    {
        // Convert from the CMS status to PoP's one
        return $this->popToCMSPostStatusConversion[$status];
    }
    public function getPosts($query, array $options = [])
    {
        // Convert the parameters
        $query = $this->convertPostsQuery($query, $options);
        return get_posts($query);
    }
    public function getPostCount($query)
    {
        // All results
        if (!isset($query['limit'])) {
            $query['limit'] = -1;
        }

        // Convert parameters
        $query = $this->convertPostsQuery($query, ['return-type' => POP_RETURNTYPE_IDS]);

        // Taken from https://stackoverflow.com/questions/2504311/wordpress-get-post-count
        $wp_query = new \WP_Query();
        $wp_query->query($query);
        return $wp_query->found_posts;
    }
    protected function convertPostsQuery($query, array $options = [])
    {
        if ($return_type = $options['return-type']) {
            if ($return_type == POP_RETURNTYPE_IDS) {
                $query['fields'] = 'ids';
            }
        }

        // Convert the parameters
        if (isset($query['post-status'])) {

            if (is_array($query['post-status'])) {
                // doing get_posts can accept an array of values
                $query['post_status'] = array_map([$this, 'convertPostStatusFromPoPToCMS'], $query['post-status']);
            } else {
                // doing wp_insert/update_post accepts a single value
                $query['post_status'] = $this->convertPostStatusFromPoPToCMS($query['post-status']);
            }
            unset($query['post-status']);
        }
        if ($query['include']) {
            // Transform from array to string
            $query['include'] = implode(',', $query['include']);
        }
        if (isset($query['post-types'])) {
            
            $query['post_type'] = $query['post-types'];
            unset($query['post-types']);
        }
        // if (isset($query['fields'])) {
        //     // Same param name, so do nothing
        // }
        if (isset($query['offset'])) {
            // Same param name, so do nothing
        }
        // if (isset($query['number']) ){ 
        //     // Same param name, so do nothing
        // }
        // if (isset($query['numberposts']) ){ 
        //     // Same param name, so do nothing
        // }
        // if (isset($query['posts-per-page'])) {
            
        //     $query['posts_per_page'] = $query['posts-per-page'];
        //     unset($query['posts-per-page']);
        // }
        if (isset($query['limit'])) {
            
            $query['posts_per_page'] = $query['limit'];
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
        // if (isset($query['meta-query'])) {
            
        //     $query['meta_query'] = $query['meta-query'];
        //     unset($query['meta-query']);
        // }
        if (isset($query['authors'])) {

            $query['author'] = implode(',', $query['authors']);
            unset($query['authors']);
        }
        // if (isset($query['date-query'])) {
            
        //     // The accepted parameter is the one expected by WordPress (https://codex.wordpress.org/Class_Reference/WP_Query)
        //     $query['date_query'] = $query['date-query'];
        //     unset($query['date-query']);
        // }
        if (isset($query['tag-ids'])) {
            
            // Watch out! In WordPress it is a string (either tag ID or comma-separated tag IDs), but in PoP it is an array of IDs!
            $query['tag_id'] = implode(',', $query['tag-ids']);
            unset($query['tag-ids']);
        }
        if (isset($query['tags']) ){ 
            
            // Watch out! In WordPress it is a string (either tag slug or comma-separated tag slugs), but in PoP it is an array of slugs!
            $query['tag'] = implode(',', $query['tags']);
            unset($query['tags']);
        }
        if (isset($query['categories'])) {
            
            // Watch out! In WordPress it is a string (either category id or comma-separated category ids), but in PoP it is an array of category ids!
            $query['cat'] = implode(',', $query['categories']);
            unset($query['categories']);
        }
        if (isset($query['post-not-in'])) {
            
            $query['post__not_in'] = $query['post-not-in'];
            unset($query['post-not-in']);
        }
        if (isset($query['category-in'])) {
            
            $query['category__in'] = $query['category-in'];
            unset($query['category-in']);
        }
        if (isset($query['category-not-in'])) {
            
            $query['category__not_in'] = $query['category-not-in'];
            unset($query['category-not-in']);
        }
        // if (isset($query['tax-query'])) {
            
        //     $query['tax_query'] = $query['tax-query'];
        //     unset($query['tax-query']);
        // }
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
            // $this->filterDateFrom = $query['date-from'];
            // unset($query['date-from']);

            // // Allow for date filtering
            // // Allow for 'posts_where' filter to be called: http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_where
            // $query['suppress_filters'] = false;
            // \PoP\CMS\HooksAPI_Factory::getInstance()->addFilter('posts_where', array(&$this, 'filterDateFrom'));
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
            // $this->filterDateTo = $query['date-to'];
            // unset($query['date-to']);

            // // Allow for date filtering
            // // Allow for 'posts_where' filter to be called: http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_where
            // $query['suppress_filters'] = false;
            // \PoP\CMS\HooksAPI_Factory::getInstance()->addFilter('posts_where', array(&$this, 'filterDateTo'));
        }
        if (isset($query['date-to-inclusive'])) {
            
            $query['date_query'][] = [
                'before' => $query['date-to-inclusive'],
                'inclusive' => true,
            ];
            unset($query['date-to-inclusive']);
        }

        $query = \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters(
            'CMSAPI:posts:query',
            $query
        );
        $this->convertPostQuerySpecialCases($query);
        return $query;
    }
    private function convertPostQuerySpecialCases(&$query)
    {
        // If both "tag" and "tax_query" were set, then the filter will not work for tags
        // Instead, what it requires is to create a nested taxonomy filtering inside the tax_query,
        // including both the tag and the already existing taxonomy filtering (eg: categories)
        // So make that transformation (https://codex.wordpress.org/Class_Reference/WP_Query#Taxonomy_Parameters)
        if ((isset($query['tag_id']) || isset($query['tag'])) && isset($query['tax_query'])) {
            // Create the tag item in the taxonomy
            $tag_slugs = [];
            if (isset($query['tag_id'])) {
                foreach (explode(',', $query['tag_id']) as $tag_id) {
                    $tag = get_tag($tag_id);
                    $tag_slugs[] = $tag->slug;
                }
            }
            if (isset($query['tag'])) {
                $tag_slugs = array_merge(
                    $tag_slugs,
                    explode(',', $query['tag'])
                );
            }
            $tag_item = array(
                'taxonomy' => 'post_tag',
                'terms' => $tag_slugs,
                'field' => 'slug'
            );

            // Will replace the current tax_query with a new one
            $tax_query = $query['tax_query'];
            $new_tax_query = array(
                'relation' => 'AND',//$tax_query['relation']
            );
            unset($tax_query['relation']);
            foreach ($tax_query as $tax_item) {
                $new_tax_query[] = array(
                    // 'relation' => 'AND',
                    $tax_item,
                    $tag_item,
                );
            }
            $query['tax_query'] = $new_tax_query;

            // The tag arg is not needed anymore
            unset($query['tag_id']);
            unset($query['tag']);
        }
    }
    public function getPostTypes($query = array())
    {
        // Convert the parameters
        if (isset($query['exclude-from-search'])) {
            
            $query['exclude_from_search'] = $query['exclude-from-search'];
            unset($query['exclude-from-search']);
        }
        return get_post_types($query);
    }
    public function getPost($post_id)
    {
        return get_post($post_id);
    }
    public function getPostType($post)
    {
        return get_post_type($post);
    }
    public function getPermalink($post_id)
    {
        if ($this->getPostStatus($post_id) == POP_POSTSTATUS_PUBLISHED) {
            return get_permalink($post_id);
        }

        // Function get_sample_permalink comes from the file below, so it must be included
        // Code below copied from `function get_sample_permalink_html`
        include_once ABSPATH.'wp-admin/includes/post.php';
        list($permalink, $post_name) = get_sample_permalink($post_id, null, null);
        return str_replace(['%pagename%', '%postname%'], $post_name, $permalink);
    }
    public function getTheExcerpt($post_id)
    {
        return get_the_excerpt($post_id);
    }
    public function getEditPostLink($post_id)
    {
        return get_edit_post_link($post_id);
    }
    public function getDeletePostLink($post_id)
    {
        return get_delete_post_link($post_id);
    }
    public function getTheTitle($post_id)
    {
        return get_the_title($post_id);
    }
    // public function getSinglePostTitle($post)
    // {
    //     // Copied from `single_post_title` in wp-includes/general-template.php
    //     return \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters('single_post_title', $post->post_title, $post);
    // }
    protected function convertQueryArgsFromPoPToCMSForInsertUpdatePost(&$query)
    {
        // Convert the parameters
        if (isset($query['post-status'])) {

            $query['post_status'] = $this->convertPostStatusFromPoPToCMS($query['post-status']);
            unset($query['post-status']);
        }
        if (isset($query['id'])) {

            $query['ID'] = $query['id'];
            unset($query['id']);
        }
        if (isset($query['post-content'])) {

            $query['post_content'] = $query['post-content'];
            unset($query['post-content']);
        }
        if (isset($query['post-title'])) {

            $query['post_title'] = $query['post-title'];
            unset($query['post-title']);
        }
        if (isset($query['post-categories'])) {

            $query['post_category'] = $query['post-categories'];
            unset($query['post-categories']);
        }
        if (isset($query['post-type'])) {

            $query['post_type'] = $query['post-type'];
            unset($query['post-type']);
        }

        // Moved to cms-functionapi-customcmscode.php
        // // WordPress-specific parameters!
        // if (isset($query['menu-order'])) {

        //     $query['menu_order'] = $query['menu-order'];
        //     unset($query['menu-order']);
        // }
    }
    public function insertPost($post_data)
    {
        // Convert the parameters
        $this->convertQueryArgsFromPoPToCMSForInsertUpdatePost($post_data);
        return wp_insert_post($post_data);
    }
    public function updatePost($post_data)
    {
        // Convert the parameters
        $this->convertQueryArgsFromPoPToCMSForInsertUpdatePost($post_data);
        return wp_update_post($post_data);
    }
    public function getPostSlug($post_id)
    {
        if ($this->getPostStatus($post_id) == POP_POSTSTATUS_PUBLISHED) {
            $post = $this->getPost($post_id);
            return $post->post_name;
        }

        // Function get_sample_permalink comes from the file below, so it must be included
        // Code below copied from `function get_sample_permalink_html`
        include_once ABSPATH.'wp-admin/includes/post.php';
        list($permalink, $post_name) = get_sample_permalink($post_id, null, null);
        return $post_name;
    }
    public function getPostTitle($post_id)
    {
        $post = $this->getPost($post_id);
        return apply_filters('the_title', $post->post_title, $post_id);
    }

    public function getPostContent($post_id)
    {
        $post = $this->getPost($post_id);
        return apply_filters('the_content', $post->post_content);
    }

    public function getPostEditorContent($post_id)
    {
        $post = $this->getPost($post_id);
        return apply_filters('the_editor_content', $post->post_content);
    }

    public function getBasicPostContent($post_id)
    {
        $post = $this->getPost($post_id);

        // Basic content: remove embeds, shortcodes, and tags
        // Remove the embed functionality, and then add again
        $wp_embed = $GLOBALS['wp_embed'];
        \PoP\CMS\HooksAPI_Factory::getInstance()->removeFilter('the_content', array( $wp_embed, 'autoembed' ), 8);

        // Do not allow HTML tags or shortcodes
        $ret = strip_shortcodes($post->post_content);
        $ret = \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters('the_content', $ret);
        \PoP\CMS\HooksAPI_Factory::getInstance()->addFilter('the_content', array( $wp_embed, 'autoembed' ), 8);

        return strip_tags($ret);
    }

    
    public function getAllowedPostTags()
    {
        global $allowedposttags;
        return $allowedposttags;
    }

    public function getExcerptMore() {

        return apply_filters('excerpt_more', ' '.'[&hellip;]');
    }

    public function getExcerptLength()
    {
        return apply_filters('excerpt_length', 55);
    }


    // Comments
    protected function convertCommentStatusFromCMSToPoP($status)
    {
        // Convert from the CMS status to PoP's one
        return $this->cmsToPoPCommentStatusConversion[$status];
    }
    protected function convertCommentStatusFromPoPToCMS($status)
    {
        // Convert from the CMS status to PoP's one
        return $this->popToCMSCommentStatusConversion[$status];
    }
    public function getComments($query, array $options = [])
    {
        if ($return_type = $options['return-type']) {
            if ($return_type == POP_RETURNTYPE_IDS) {
                $query['fields'] = 'ids';
            }
        }
        // Convert the parameters
        if (isset($query['status'])) {

            $query['status'] = $this->convertCommentStatusFromPoPToCMS($query['status']);
        }
        if (isset($query['post-id'])) {

            $query['post_id'] = $query['post-id'];
            unset($query['post-id']);
        }
        if (isset($query['user-id'])) {

            $query['user_id'] = $query['user-id'];
            unset($query['user-id']);
        }
        if (isset($query['authors'])) {

            // Only 1 author is accepted
            $query['user_id'] = $query['authors'][0];
            unset($query['authors']);
        }
        if (isset($query['order'])) {
            // Same param name, so do nothing
        }
        if (isset($query['orderby'])) {
            // Same param name, so do nothing
            // This param can either be a string or an array. Eg:
            // $query['orderby'] => array('date' => 'DESC', 'title' => 'ASC');
        }
        // if (isset($query['fields'])) {
        //     // Same param name, so do nothing
        // }
        // if (isset($query['number'])) {
        //     // Same param name, so do nothing
        // }
        // For the comments, if there's no limit then it brings all results
        if ($query['limit']) {
            
            $query['number'] = $query['limit'];
            unset($query['limit']);
        }
        if (isset($query['search'])) {
            // Same param name, so do nothing
        }
        // Only comments, no trackbacks or pingbacks
        $query['type'] = 'comment'; 

        $query = \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters(
            'CMSAPI:comments:query',
            $query
        );
        return get_comments($query);
    }
    public function getComment($comment_id)
    {
        return get_comment($comment_id);
    }
    public function insertComment($comment_data)
    {
        // Convert the parameters
        if (isset($comment_data['user-id'])) {

            $comment_data['user_id'] = $comment_data['user-id'];
            unset($comment_data['user-id']);
        }
        if (isset($comment_data['author'])) {

            $comment_data['comment_author'] = $comment_data['author'];
            unset($comment_data['author']);
        }
        if (isset($comment_data['author-email'])) {

            $comment_data['comment_author_email'] = $comment_data['author-email'];
            unset($comment_data['author-email']);
        }
        if (isset($comment_data['author-URL'])) {

            $comment_data['comment_author_url'] = $comment_data['author-URL'];
            unset($comment_data['author-URL']);
        }
        if (isset($comment_data['author-IP'])) {

            $comment_data['comment_author_IP'] = $comment_data['author-IP'];
            unset($comment_data['author-IP']);
        }
        if (isset($comment_data['agent'])) {

            $comment_data['comment_agent'] = $comment_data['agent'];
            unset($comment_data['agent']);
        }
        if (isset($comment_data['content'])) {

            $comment_data['comment_content'] = $comment_data['content'];
            unset($comment_data['content']);
        }
        if (isset($comment_data['parent'])) {

            $comment_data['comment_parent'] = $comment_data['parent'];
            unset($comment_data['parent']);
        }
        if (isset($comment_data['post-id'])) {

            $comment_data['comment_post_ID'] = $comment_data['post-id'];
            unset($comment_data['post-id']);
        }
        return wp_insert_comment($comment_data);
    }
    public function getCommentsNumber($post_id)
    {
        return get_comments_number($post_id);
    }


    // Menus
    public function getNavMenuLocations()
    {
        return get_nav_menu_locations();
    }
    public function getNavigationMenuObject($menu_object_id)
    {
        return wp_get_nav_menu_object($menu_object_id);
    }
    public function getNavigationMenuItems($menu)
    {
        return wp_get_nav_menu_items($menu);
    }
    public function getMenuItemTitle($menu_item) {

        return apply_filters('the_title', $menu_item->title, $menu_item->object_id);
    }


    // Media
    public function getAttachmentImageSrc($image_id, $size = null)
    {
        return wp_get_attachment_image_src($image_id, $size);
    }
    public function getPostMimeType($post_thumb_id)
    {
        return get_post_mime_type($post_thumb_id);
    }


    // PostMedia
    public function hasPostThumbnail($post_id)
    {
        return has_post_thumbnail($post_id);
    }
    public function getPostThumbnailId($post_id)
    {
        return get_post_thumbnail_id($post_id);
    }
    

    // Taxonomies
    public function getTrendingHashtagIds($days, $number, $offset)
    {        
        global $wpdb;
        
        // Solution to get the Trending Tags taken from https://wordpress.org/support/topic/limit-tags-by-date
        $time_difference = $this->getOption(\PoP\CMS\NameResolver_Factory::getInstance()->getName('popcms:option:gmtOffset')) * HOUR_IN_SECONDS;
        $timenow = time() + $time_difference;
        $timelimit = $timenow - ($days * 24 * HOUR_IN_SECONDS);
        $now = gmdate('Y-m-d H:i:s', $timenow);
        $datelimit = gmdate('Y-m-d H:i:s', $timelimit);
        
        $sql = "
            SELECT 
                $wpdb->terms.term_id, 
                COUNT($wpdb->terms.term_id) as count 
            FROM 
                $wpdb->posts, 
                $wpdb->term_relationships, 
                $wpdb->term_taxonomy, 
                $wpdb->terms 
            WHERE 
                $wpdb->posts.ID = $wpdb->term_relationships.object_id AND 
                $wpdb->term_taxonomy.term_taxonomy_id = $wpdb->term_relationships.term_taxonomy_id AND 
                $wpdb->term_taxonomy.term_id = $wpdb->terms.term_id AND 
                post_status = 'publish' AND 
                post_date < '$now' AND 
                post_date > '$datelimit' AND 
                $wpdb->term_taxonomy.taxonomy='post_tag' 
            GROUP BY 
                $wpdb->terms.term_id 
            ORDER BY 
                count 
            DESC
        ";

        // Use pagination if a limit was set
        if ($number && (intval($number) > 0)) {
            $sql .= sprintf(
                ' LIMIT %s',
                intval($number)
            );
        }
        if ($offset && (intval($offset) > 0)) {
            $sql .= sprintf(
                ' OFFSET %s',
                intval($offset)
            );
        }
        
        $ids = array();
        if ($results = $wpdb->get_results($sql)) {
            foreach ($results as $result) {
                $ids[] = $result->term_id;
            }
        }

        return $ids;
    }


    // Users
    public function getUserById($value)
    {
        return get_user_by('id', $value);
    }
    public function getUserByEmail($value)
    {
        return get_user_by('email', $value);
    }
    public function getUserBySlug($value)
    {
        return get_user_by('slug', $value);
    }
    public function getUserByLogin($value)
    {
        return get_user_by('login', $value);
    }
    public function getUsers($query = array(), array $options = [])
    {
        if ($return_type = $options['return-type']) {
            if ($return_type == POP_RETURNTYPE_IDS) {
                $query['fields'] = 'ID';
            }
        }

        // Convert parameters
        // if (isset($query['fields'])) {
        //     // Same param name, so do nothing
        // }
        if (isset($query['name'])) {
            $query['meta_query'][] = [
                'key' => 'nickname',
                'value' => $query['name'],
                'compare' => 'LIKE',
            ];
            unset($query['name']);
        }
        if (isset($query['role'])) {
            // Same param name, so do nothing
        }
        if ($query['include']) {
            // Transform from array to string
            $query['include'] = implode(',', $query['include']);
        }
        if (isset($query['exclude'])) {
            // Transform from array to string
            $query['exclude'] = implode(',', $query['exclude']);
        }
        if (isset($query['order'])) {
            // Same param name, so do nothing
        }
        if (isset($query['orderby'])) {
            // Same param name, so do nothing
            // This param can either be a string or an array. Eg:
            // $query['orderby'] => array('date' => 'DESC', 'title' => 'ASC');
        }
        if (isset($query['offset'])) {
            // Same param name, so do nothing
        }
        // if (isset($query['number'])) {
        //     // Same param name, so do nothing
        // }
        if (isset($query['limit'])) {
            
            $query['number'] = $query['limit'];
            unset($query['limit']);
        }
        // if (isset($query['meta-query']) ){ 
            
        //     $query['meta_query'] = $query['meta-query'];
        //     unset($query['meta-query']);
        // }
        if (isset($query['role-in']) ){ 
            
            $query['role__in'] = $query['role-in'];
            unset($query['role-in']);
        }
        if (isset($query['role-not-in']) ){ 
            
            $query['role__not_in'] = $query['role-not-in'];
            unset($query['role-not-in']);
        }

        $query = \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters(
            'CMSAPI:users:query',
            $query
        );
        return get_users($query);
    }
    public function getTheAuthorMeta($field, $user_id)
    {
        return get_the_author_meta($field, $user_id);
    }
    public function getUserDisplayName($user_id)
    {
        return $this->getTheAuthorMeta('display_name', $user_id);
    }
    public function getUserEmail($user_id)
    {
        return $this->getTheAuthorMeta('user_email', $user_id);
    }
    public function getUserFirstname($user_id)
    {
        return $this->getTheAuthorMeta('user_firstname', $user_id);
    }
    public function getUserLastname($user_id)
    {
        return $this->getTheAuthorMeta('user_lastname', $user_id);
    }
    public function getUserLogin($user_id)
    {
        return $this->getTheAuthorMeta('user_login', $user_id);
    }
    public function getUserDescription($user_id)
    {
        return $this->getTheAuthorMeta('description', $user_id);
    }
    public function getUserRegistrationDate($user_id)
    {
        return $this->getTheAuthorMeta('user_registered', $user_id);
    }
    public function getAuthorPostsUrl($user_id)
    {
        return get_author_posts_url($user_id);
    }

    public function isUserLoggedIn()
    {
        return is_user_logged_in();
    }
    public function getCurrentUser()
    {
        return wp_get_current_user();
    }
    public function getCurrentUserId()
    {
        return get_current_user_id();
    }
    
    public function getAuthorBase()
    {
        global $wp_rewrite;
        return $wp_rewrite->author_base;
    }

    public function getPasswordResetKey($user_data)
    {
        $result = get_password_reset_key($user_data);
        return $this->returnResultOrConvertError($result);
    }

    public function logout()
    {
        wp_logout();

        // Delete the current user, so that it already says "user not logged in" for the toplevel feedback
        global $current_user;
        $current_user = null;
        wp_set_current_user(0);
    }

    public function login($credentials)
    {
        // Convert params
        if (isset($credentials['login'])) {

            $credentials['user_login'] = $credentials['login'];
            unset($credentials['login']);
        }
        if (isset($credentials['password'])) {

            $credentials['user_password'] = $credentials['password'];
            unset($credentials['password']);
        }
        if (isset($credentials['remember'])) {
            // Same param name, so do nothing
        }
        $result = wp_signon($credentials);
        
        // Set the current user already, so that it already says "user logged in" for the toplevel feedback
        if (!$this->isError($result)) {
            $user = $result;
            wp_set_current_user($user->ID);
        }
        
        return $this->returnResultOrConvertError($result);
    }

    public function checkPassword($password, $hash)
    {
        return wp_check_password($password, $hash);
    }

    public function checkPasswordResetKey($key, $login)
    {
        $result = check_password_reset_key($key, $login);
        return $this->returnResultOrConvertError($result);
    }

    protected function convertQueryArgsFromPoPToCMSForInsertUpdateUser(&$query)
    {
        // Convert the parameters
        if (isset($query['id'])) {

            $query['ID'] = $query['id'];
            unset($query['id']);
        }
        if (isset($query['firstname'])) {

            $query['first_name'] = $query['firstname'];
            unset($query['firstname']);
        }
        if (isset($query['lastname'])) {

            $query['last_name'] = $query['lastname'];
            unset($query['lastname']);
        }
        if (isset($query['email'])) {

            $query['user_email'] = $query['email'];
            unset($query['email']);
        }
        if (isset($query['description'])) {
            // Same param name, so do nothing
        }
        if (isset($query['url'])) {
            
            $query['user_url'] = $query['url'];
            unset($query['url']);
        }
        if (isset($query['role'])) {
            // Same param name, so do nothing
        }
        if (isset($query['password'])) {

            $query['user_pass'] = $query['password'];
            unset($query['password']);
        }
        if (isset($query['login'])) {

            $query['user_login'] = $query['login'];
            unset($query['login']);
        }
    }
    public function insertUser($user_data)
    {
        $this->convertQueryArgsFromPoPToCMSForInsertUpdateUser($user_data);
        $result = wp_insert_user($user_data);
        return $this->returnResultOrConvertError($result);
    }
    public function updateUser($user_data)
    {
        $this->convertQueryArgsFromPoPToCMSForInsertUpdateUser($user_data);
        $result = wp_update_user($user_data);
        return $this->returnResultOrConvertError($result);
    }

    public function resetPassword($user, $pwd)
    {
        return reset_password($user, $pwd);
    }

    public function getLoginURL()
    {
        return wp_login_url();
    }

    public function getLogoutURL()
    {
        return wp_logout_url();
    }

    public function getLostPasswordURL()
    {
        return wp_lostpassword_url();
    }

    // User Roles
    public function addRole($role, $display_name, $capabilities = array())
    {
        add_role($role, $display_name, $capabilities);
    }
    public function addRoleToUser($user_id, $role)
    {
        $user = $this->getUserById($user_id);
        $user->add_role($role);
    }
    public function removeRoleFromUser($user_id, $role)
    {
        $user = $this->getUserById($user_id);
        $user->remove_role($role);
    }
    public function getUserRoles($user_id)
    {
        $user = $this->getUserById($user_id);
        return $user->roles;
    }
    public function getTheUserRole($user_id)
    {
        return get_the_user_role($user_id);
    }
    public function userCan($user_id, $capability)
    {
        return user_can($user_id, $capability);
    }

    
    // public function filterDateFrom($where = '')
    // {
    //     global $wpdb;
    //     if ($this->filterDateFrom) {
            
    //         // Make sure that the dates are proper dates (protection against hackers) <= data validation/sanitation
    //         $this->filterDateFrom = date('Y-m-d', strtotime($this->filterDateFrom));
    //         $where .= " AND {$wpdb->posts}.post_date >= '".$this->filterDateFrom."'";
    //     }
    //     unset($this->filterDateFrom);

    //     // Remove myself
    //     \PoP\CMS\HooksAPI_Factory::getInstance()->removeFilter('posts_where', array(&$this, 'filterDateFrom'));

    //     return $where;
    // }
    // public function filterDateTo($where = '')
    // {
    //     global $wpdb;
    //     if ($this->filterDateTo) {

    //         // Make sure that the dates are proper dates (protection against hackers) <= data validation/sanitation
    //         $this->filterDateTo = date('Y-m-d', strtotime($this->filterDateTo));
    //         $where .= " AND {$wpdb->posts}.post_date < '".$this->filterDateTo."'";
    //     }
    //     unset($this->filterDateTo);

    //     // Remove myself
    //     \PoP\CMS\HooksAPI_Factory::getInstance()->removeFilter('posts_where', array(&$this, 'filterDateTo'));

    //     return $where;
    // }
    // public function getObjectTaxonomies($object, $output = 'names')
    // {
    //     return get_object_taxonomies($object, $output);
    // }
    // public function getUserdata($user_id)
    // {
    //     return get_userdata($user_id);
    // }

    
    
    
    
}

/**
 * Initialize
 */
new FunctionAPI();
