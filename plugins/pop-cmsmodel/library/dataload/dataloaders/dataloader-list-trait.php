<?php
namespace PoP\CMSModel;

trait Dataloader_ListTrait
{
    use \PoP\Engine\FilterQueryDataloaderTrait;
    
    /**
     * Function to override
     */
    public function executeQuery($query)
    {
        return array();
    }
    
    public function executeQueryIds($query)
    {
        return $this->executeQuery($query);
    }

    protected function getPagedParam($query_args)
    {
        
        // Allow to check for PoP_Application_Engine_Utils::loadingLatest():
        return \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters(
            'GD_Dataloader_List:query:paged',
            $query_args[GD_URLPARAM_PAGED]
        );
    }
    protected function getLimitParam($query_args)
    {
        return $this->getMetaLimitParam($query_args);
    }
    protected function getMetaLimitParam($query_args)
    {
        
        // Allow to check for PoP_Application_Engine_Utils::loadingLatest():
        return \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters(
            'GD_Dataloader_List:query:limit',
            $query_args[GD_URLPARAM_LIMIT]
        );
    }
    
    public function getDbobjectIds($data_properties)
    {
        $query_args = $data_properties[GD_DATALOAD_QUERYARGS];

        // If already indicating the ids to get back, then already return them
        if ($include = $query_args['include']) {
            if (!is_array($include)) {
                return explode(',', $include);
            }

            return $include;
        }

        // Customize query
        $query = $this->getQuery($query_args);

        // Allow URE to modify the role, limiting selected users and excluding others, like 'subscriber'
        $query = \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters('gd_dataload_query:'.$this->getName(), $query, $data_properties);
                
        // Apply filtering of the data
        $query = $this->filterQuery($query, $data_properties);

        // Execute the query, get ids
        $ids = $this->executeQueryIds($query);
        
        // Allow $gd_filter to clear (remove unneeded filters)
        $this->clearFilter();
        
        return $ids;
    }
    
    /**
     * Function to override
     */
    public function getDataFromIdsQuery($ids)
    {
        return array();
    }
    
    public function executeGetData($ids)
    {
        $query = $this->getDataFromIdsQuery($ids);
        return $this->executeQuery($query);
    }

    protected function getOrderbyDefault()
    {
        return '';
    }

    protected function getOrderDefault()
    {
        return '';
    }

    protected function getMetaQuery($query_args)
    {
        $query = array();

        // Allow to check for PoP_Application_Engine_Utils::loadingLatest()
        $paged = $this->getPagedParam($query_args);
        $limit = $this->getLimitParam($query_args);
        if ($limit >= 1) {
            $offset = ($paged - 1) * $limit;
            $query['offset'] = $offset;
            $query['number'] = $limit;
        } else {
            // $limit is 0 (EM) or -1 (WP)
            $query['numberposts'] = $limit;
        }

        // Params and values by default
        if ($orderby = isset($query_args['orderby']) ? $query_args['orderby'] : $this->getOrderbyDefault()) {
            $query['orderby'] = $orderby;
        }
        if ($order = isset($query_args['order']) ? $query_args['order'] : $this->getOrderDefault()) {
            $query['order'] = $order;
        }

        // Metaquery: eg: filter only Actions with Locations
        if ($meta_query = $query_args['meta-query']) {
            $query['meta_query'] = $meta_query;
        }

        return $query;
    }
     
    /**
     * Function to override
     */
    public function getQuery($query_args)
    {
        return $this->getMetaQuery($query_args);
    }
}
