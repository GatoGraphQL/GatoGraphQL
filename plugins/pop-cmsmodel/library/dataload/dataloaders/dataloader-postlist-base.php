<?php
namespace PoP\CMSModel;

abstract class Dataloader_PostListBase extends Dataloader_PostBase
{
    use Dataloader_ListTrait;

    /**
     * Function to override
     */
    public function getDataFromIdsQuery(array $ids): array
    {
        $query = array();
        $query['include'] = $ids;
        $query['post-status'] = [
            POP_POSTSTATUS_PUBLISHED, 
            POP_POSTSTATUS_DRAFT, 
            POP_POSTSTATUS_PENDING,
        ]; // Post status can also be 'pending', so don't limit it here, just select by ID

        // Allow absolutely any post type, including events and highlights
        $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
        $query['post-types'] = array_keys($cmsapi->getPostTypes());
        
        return $query;
    }
    
    public function executeQuery($query, array $options = [])
    {
        $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
        return $cmsapi->getPosts($query, $options);
    }

    protected function getOrderbyDefault()
    {
        return \PoP\CMS\NameResolver_Factory::getInstance()->getName('popcms:dbcolumn:orderby:posts:date');
    }

    protected function getOrderDefault()
    {
        return 'DESC';
    }
    
    public function executeQueryIds($query)
    {
        // $query['fields'] = 'ids';
        $options = [
            'return-type' => POP_RETURNTYPE_IDS,
        ];
        return $this->executeQuery($query, $options);
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
    // public function getQuery($query_args)
    // {
    //     $query = $this->getMetaQuery($query_args);

    //     // // if ($limit = $query['number']) {
    //     // //     $query['posts-per-page'] = $limit;
    //     // //     unset($query['number']);
    //     // //     unset($query['numberposts']);
    //     // // }
        
    //     // if ($authors = $query_args['authors']) {
    //     //     $query['authors'] = $authors;
    //     // }

    //     // // if ($date_query = $query_args['date-query']) {
    //     // //     $query['date-query'] = $date_query;
    //     // // }

    //     // if ($date_query = $query_args['date-from']) {
    //     //     $query['date-from'] = $date_query;
    //     // }
    //     // if ($date_query = $query_args['date-from-inclusive']) {
    //     //     $query['date-from-inclusive'] = $date_query;
    //     // }
    //     // if ($date_query = $query_args['date-to']) {
    //     //     $query['date-to'] = $date_query;
    //     // }
    //     // if ($date_query = $query_args['date-to-inclusive']) {
    //     //     $query['date-to-inclusive'] = $date_query;
    //     // }

    //     // // Tags
    //     // if ($tag_ids = $query_args['tag-ids']) {
    //     //     $query['tag-ids'] = $tag_ids;
    //     // }
    //     // if ($tags = $query_args['tags']) {
    //     //     $query['tags'] = $tags;
    //     // }

    //     // // Category
    //     // if ($categories = $query_args['categories']) {
    //     //     $query['categories'] = $categories;
    //     // }

    //     // // post__not_in to remove posts in the Hierarchy (eg: remove current Single post for Block Related)
    //     // if ($post_not_in = $query_args['post-not-in']) {
    //     //     $query['post-not-in'] = $post_not_in;
    //     // }

    //     // if ($category__in = $query_args['category-in']) {
    //     //     $query['category-in'] = $category__in;
    //     // }

    //     // if ($category__not_in = $query_args['category-not-in']) {
    //     //     $query['category-not-in'] = $category__not_in;
    //     // }

    //     // // Tax Query: eg: to bring all different content for the Latest Everything Block
    //     // if ($post_types = $query_args['post-types']) {
    //     //     $query['post-types'] = $post_types;
    //     // }
    //     // if ($tax_query = $query_args['tax-query']) {
    //     //     $query['tax-query'] = $tax_query;
    //     // }

    //     // // Post status: added for selecting the ID / Nonce of a newly created post (which is 'pending')
    //     // if ($post_status = $query_args['post-status']) {
    //     //     $query['post-status'] = $post_status;
    //     // }

    //     // Allow to add the timestamp for loadingLatest
    //     return \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters(
    //         'Dataloader_PostListBase:query',
    //         $query,
    //         $query_args
    //     );
    // }

    protected function getQueryHookName() {

        // Allow to add the timestamp for loadingLatest
        return 'Dataloader_PostListBase:query';
    }
}
