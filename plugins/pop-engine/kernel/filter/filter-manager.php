<?php
namespace PoP\Engine;

class Filter_Manager
{
    public $filters;
    
    public function __construct()
    {
        $this->filters = array();
    }
    
    public function add($filter)
    {
        $this->filters[$filter->getName()] = $filter;
    }

    public function getFilter($filtername)
    {
        return $this->filters[$filtername];
    }

    /**
     * Returns the currently used filter
     */
    public function getFilteringbyFilter()
    {
        $filtername = $_REQUEST[POP_FILTER_FILTERING_FIELD];
        return $this->getFilter($filtername);
    }
    
    /**
     * Returns true if it is filtering by the given $filter
     */
    public function filteringby($filter_or_filtername)
    {
        if (is_object($filter_or_filtername)) {
            $filter = $filter_or_filtername;
        } else {
            global $POP_FILTER_manager;
            $filtername = $filter_or_filtername;
            $filter = $POP_FILTER_manager->getFilter($filtername);
        }
    
        $wildcard_filters = array();
        $wildcardfilter = $filter;
        
        // Wildcard filters: both filters and wildcard filters can specify, so bring all of them down the road
        // while ($wildcardfilter = $wildcardfilter->getWildcardFilter()) {
        while ($wildcardfilter_name = $wildcardfilter->getWildcardFilter()) {
            $wildcard_filters[] = $wildcardfilter_name;
            $wildcardfilter = $this->getFilter($wildcardfilter_name);
        }

        // Filtering by the same Filter or its wildcard (eg: a General Post Search is a wildcard, it involves all posts filters like Announcements Filter)
        $requested_filtername = $_REQUEST[POP_FILTER_FILTERING_FIELD];
        return ($requested_filtername == $filter->getName() || (!empty($wildcard_filters) && in_array($requested_filtername, $wildcard_filters)));
    }

    public function filterQuery($query, $data_properties)
    {

        // Allow $gd_filter to filter the query
        $query = apply_filters('filterQuery', $query, $data_properties);
    
        // Validate currently filtering by this blocks' filter
        $filter_name = $data_properties[GD_DATALOAD_FILTER];
        if (!$filter_name || !$this->filteringby($filter_name)) {
            return $query;
        }

        global $POP_FILTER_manager;
        $filter = $POP_FILTER_manager->getFilter($filter_name);
        
        // Merge with the filter query filters
        // Using array_merge_recursive on 'meta-query', which can be set in different places (in the filter, eg: when filtering Action category)
        // (and in the Action map to filter all Actions which have a location)
        // Important: Make sure there is not empty string in the query! (Eg: 'author' => '') because with array_merge_recursive
        // will also process that one element, making an array when it should be a string

        $query = array_merge_recursive($query, $filter->getFilterArgs($data_properties));
        $query = array_merge($query, $filter->getFilterArgsOverrideValues($data_properties));

        // If both "tag" and "tax_query" were set, then the filter will not work for tags
        // Instead, what it requires is to create a nested taxonomy filtering inside the tax_query,
        // including both the tag and the already existing taxonomy filtering (eg: categories)
        // So make that transformation (https://codex.wordpress.org/Class_Reference/WP_Query#Taxonomy_Parameters)
        $vars = Engine_Vars::getVars();
        if (($vars['global-state']['is-tag'] || $query['tag']) && $query['tax_query']) {
            // Create the tag item in the taxonomy
            $tag_slug = '';
            if ($vars['global-state']['is-tag']) {
                $tag = $vars['global-state']['queried-object'];
                $tag_slug = $tag->slug;
            } else {
                $tag_slug = $query['tag'];
            }
            $tag_item = array(
                'taxonomy' => 'post_tag',
                'terms' => explode(',', $tag_slug),
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
            if ($query['tag']) {
                unset($query['tag']);
            }
        }
        $filter->filterQuery($query, $data_properties);

        // Allow for date filtering
        $query['suppress_filters'] = false;        // Allow for 'posts_where' filter to be called: http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_where
        addFilter('posts_where', array(&$filter, 'filterWhere'));

        return $query;
    }
    
    public function clearFilter()
    {

        // Allow $gd_filter to clear (remove unneeded filters)
        do_action('clearFilter');

        if (!($filter = $this->getFilteringbyFilter())) {
            return;
        }

        // Remove the filters
        removeFilter('posts_where', array(&$filter, 'filterWhere'));
    }

    /**
     * Adds the params to filter to the url
     */
    public function addFilterParams($url, $filter, $args = array())
    {
    
        // Add the 'filter' param
        $url = add_query_arg(POP_FILTER_FILTERING_FIELD, $filter->getName(), $url);
        foreach ($args as $field => $value) {
            $url = add_query_arg($field, $value, $url);
        }
        
        return $url;
    }
}

/**
 * Initialization
 */
global $POP_FILTER_manager;
$POP_FILTER_manager = new Filter_Manager();
