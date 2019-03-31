<?php
namespace PoP\CMSModel;

trait Dataloader_ListTrait
{
    // use \PoP\Engine\FilterQueryDataloaderTrait;
    
    /**
     * Function to override
     */
    public function executeQuery($query, array $options = [])
    {
        return array();
    }
    
    public function executeQueryIds($query)
    {
        return $this->executeQuery($query);
    }

    protected function getPagenumberParam($query_args)
    {
        
        // Allow to check for PoP_Application_Engine_Utils::loadingLatest():
        return \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters(
            'GD_Dataloader_List:query:pagenumber',
            $query_args[GD_URLPARAM_PAGENUMBER]
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
            // if (!is_array($include)) {
            //     return explode(',', $include);
            // }

            return $include;
        }

        // Customize query
        $query = $this->getQuery($query_args);

        // Allow URE to modify the role, limiting selected users and excluding others, like 'subscriber'
        $query = \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters('gd_dataload_query:'.$this->getName(), $query, $data_properties);
                
        // Apply filtering of the data
        if ($filtering_modules = $data_properties[GD_DATALOAD_QUERYARGSFILTERINGMODULES]) {
            $moduleprocessor_manager = \PoP\Engine\ModuleProcessor_Manager_Factory::getInstance();
            foreach ($filtering_modules as $module) {
                $moduleprocessor_manager->getProcessor($module)->filterHeadmoduleDataloadQueryArgs($query, $module);
            }
        }

        // Execute the query, get ids
        $ids = $this->executeQueryIds($query);
        
        return $ids;
    }
    
    /**
     * Function to override
     */
    public function getDataFromIdsQuery(array $ids): array
    {
        return array();
    }
    
    public function executeGetData(array $ids): array
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
        // $query = array();
        $query = $query_args;

        // Allow to check for PoP_Application_Engine_Utils::loadingLatest()
        $limit = $this->getLimitParam($query_args);
        $query['limit'] = $limit;
        $pagenumber = $this->getPagenumberParam($query_args);
        if ($pagenumber >= 2) {
            $query['offset'] = ($pagenumber - 1) * $limit;
        }
        // if ($limit >= 1) {
        //     $offset = ($pagenumber - 1) * $limit;
        //     $query['offset'] = $offset;
        //     $query['number'] = $limit;
        // } else {
        //     // $limit is 0 (EM) or -1 (WP)
        //     $query['numberposts'] = $limit;
        // }

        // Params and values by default
        if (!$query['orderby']) {
            $query['orderby'] = $this->getOrderbyDefault();
        }
        if (!$query['order']) {
            $query['order'] = $this->getOrderDefault();
        }
        // if ($orderby = isset($query_args['orderby']) ? $query_args['orderby'] : $this->getOrderbyDefault()) {
        //     $query['orderby'] = $orderby;
        // }
        // if ($order = isset($query_args['order']) ? $query_args['order'] : $this->getOrderDefault()) {
        //     $query['order'] = $order;
        // }

        // // Metaquery: eg: filter only Actions with Locations
        // if ($meta_query = $query_args['meta-query']) {
        //     $query['meta-query'] = $meta_query;
        // }

        return $query;
    }

    protected function getQueryHookName() {

        return 'Dataloader_ListTrait:query';
    }
     
    /**
     * Function to override
     */
    public function getQuery($query_args)
    {
        $query = $this->getMetaQuery($query_args);

        // Allow CoAuthors Plus to modify the query to add the coauthors
        return \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters(
            $this->getQueryHookName(),
            $query,
            $query_args
        );
    }
}
