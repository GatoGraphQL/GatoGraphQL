<?php
namespace PoP\CMSModel;

abstract class Dataloader_PostListBase extends Dataloader_PostBase
{
    use Dataloader_ListTrait;

    /**
     * Function to override
     */
    public function getDataFromIdsQuery($ids)
    {
        $query = array();
        $query['include'] = $ids;
        $query['post_status'] = 'any'; // Post status can also be 'pending', so don't limit it here, just select by ID

        // Allow absolutely any post type, including events and highlights
        $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
        $query['post_type'] = array_keys($cmsapi->getPostTypes());
        
        return $query;
    }
    
    public function executeQuery($query)
    {
        $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
        return $cmsapi->getPosts($query);
    }

    protected function getOrderbyDefault()
    {
        return 'date';
    }

    protected function getOrderDefault()
    {
        return 'DESC';
    }
    
    public function executeQueryIds($query)
    {
        $query['fields'] = 'ids';
        return $this->executeQuery($query);
    }

    protected function getLimitParam($query_args)
    {
        
        // Allow to check for PoP_Application_Engine_Utils::loadingLatest():
        return \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters(
            'Dataloader_PostListBase:query:limit',
            $this->getMetaLimitParam($query_args)
        );
    }
    
    /**
     * Function to override
     */
    public function getQuery($query_args)
    {
        $query = $this->getMetaQuery($query_args);

        if ($limit = $query['number']) {
            $query['posts_per_page'] = $limit;
            unset($query['number']);
            unset($query['numberposts']);
        }
        
        if ($author = $query_args['author']) {
            $query['author'] = $author;
        }

        if ($date_query = $query_args['date_query']) {
            $query['date_query'] = $date_query;
        }

        // Tags
        if ($tag_id = $query_args['tag-id']) {
            $query['tag_id'] = $tag_id;
        }
        if ($tag = $query_args['tag']) {
            $query['tag'] = $tag;
        }

        // Category
        if ($cat = $query_args['cat']) {
            $query['cat'] = $cat;
        }

        // post__not_in to remove posts in the Hierarchy (eg: remove current Single post for Block Related)
        if ($post_not_in = $query_args['post__not_in']) {
            $query['post__not_in'] = $post_not_in;
        }

        if ($category__in = $query_args['category-in']) {
            $query['category__in'] = $category__in;
        }

        if ($category__not_in = $query_args['category-not-in']) {
            $query['category__not_in'] = $category__not_in;
        }

        // Tax Query: eg: to bring all different content for the Latest Everything Block
        if ($post_type = $query_args['post-type']) {
            $query['post_type'] = $post_type;
        }
        if ($tax_query = $query_args['tax-query']) {
            $query['tax_query'] = $tax_query;
        }

        // Post status: added for selecting the ID / Nonce of a newly created post (which is 'pending')
        if ($post_status = $query_args['post-status']) {
            $query['post_status'] = $post_status;
        }

        // Allow for co-authors plus plug-in to add "posts_where_filter"
        $query['suppress_filters'] = false;

        // Allow to add the timestamp for loadingLatest
        return \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters(
            'Dataloader_PostListBase:query',
            $query,
            $query_args
        );
    }
}
