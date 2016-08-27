<?php
class EM_Categories extends EM_Object implements Iterator{
	
	/**
	 * Array of EM_Category objects for a specific event
	 * @var array
	 */
	var $categories = array();
	/**
	 * Event ID of this set of categories
	 * @var int
	 */
	var $event_id;
	/**
	 * Post ID of this set of categories
	 * @var int
	 */
	var $post_id;
	
	/**
	 * Creates an EM_Categories instance, currently accepts an EM_Event object (gets all Categories for that event) or array of any EM_Category objects, which can be manipulated in bulk with helper functions.
	 * @param mixed $data
	 * @return null
	 */
	function __construct( $data = false ){
		global $wpdb;
		self::ms_global_switch();
		if( is_object($data) && get_class($data) == "EM_Event" && !empty($data->post_id) ){ //Creates a blank categories object if needed
			$this->event_id = $data->event_id;
			$this->post_id = $data->post_id;
			if( EM_MS_GLOBAL && !is_main_site($data->blog_id) ){
				$cat_ids = $wpdb->get_col('SELECT meta_value FROM '.EM_META_TABLE." WHERE object_id='{$this->event_id}' AND meta_key='event-category'");
				foreach($cat_ids as $cat_id){
					$this->categories[$cat_id] = new EM_Category($cat_id);
				}
			}else{
				$results = get_the_terms( $data->post_id, EM_TAXONOMY_CATEGORY );
				if( is_array($results) ){
					foreach($results as $result){
						$this->categories[$result->term_id] = new EM_Category($result);
					}
				}
			}
		}elseif( is_array($data) && self::array_is_numeric($data) ){
			foreach($data as $category_id){
				$this->categories[$category_id] =  new EM_Category($category_id);
			}
		}elseif( is_array($data) ){
			foreach( $data as $EM_Category ){
				if( get_class($EM_Category) == 'EM_Category'){
					$this->categories[] = $EM_Category;
				}
			}
		}
		self::ms_global_switch_back();
		do_action('em_categories', $this);
	}
	
	function get_post(){
		self::ms_global_switch();
		$this->categories = array();
		if(!empty($_POST['event_categories']) && self::array_is_numeric($_POST['event_categories'])){
			foreach( $_POST['event_categories'] as $term ){
				$this->categories[$term] = new EM_Category($term);
			}
		}
		self::ms_global_switch_back();
		do_action('em_categories_get_post', $this);
	}
	
	function save(){
		$term_slugs = array();
		foreach($this->categories as $EM_Category){
			/* @var $EM_Category EM_Category */
			if( !empty($EM_Category->slug) ) $term_slugs[] = $EM_Category->slug; //save of category will soft-fail if slug is empty
		}
		if( count($term_slugs) == 0 && get_option('dbem_default_category') > 0 ){
			$default_term = get_term_by('id',get_option('dbem_default_category'), EM_TAXONOMY_CATEGORY);
			if($default_term) $term_slugs[] = $default_term->slug;
		}
		if( count($term_slugs) > 0 ){
			if( is_multisite() ){
				//In MS Global mode, we also save category meta information for global lookups
				if( EM_MS_GLOBAL && !empty($this->event_id) ){
					//delete categories
					$this->save_index();
				}
				if( !EM_MS_GLOBAL || is_main_site() ){
					wp_set_object_terms($this->post_id, $term_slugs, EM_TAXONOMY_CATEGORY);
				}
			}else{
				wp_set_object_terms($this->post_id, $term_slugs, EM_TAXONOMY_CATEGORY);
			}			
		}
		do_action('em_categories_save', $this);
	}
	
	function save_index(){
		global $wpdb;
		$wpdb->query('DELETE FROM '.EM_META_TABLE." WHERE object_id='{$this->event_id}' AND meta_key='event-category'");
		foreach($this->categories as $EM_Category){
			$wpdb->insert(EM_META_TABLE, array('meta_value'=>$EM_Category->term_id,'object_id'=>$this->event_id,'meta_key'=>'event-category'));
		}
	}
		
	public static function get( $args = array() ) {		
		//Quick version, we can accept an array of IDs, which is easy to retrieve
		self::ms_global_switch();
		if( self::array_is_numeric($args) ){ //Array of numbers, assume they are event IDs to retreive
			$results = get_terms( EM_TAXONOMY_CATEGORY );
			$categories = array();
			foreach($results as $result){
				if( in_array($result->term_id, $args) ){
					$categories[$result->term_id] = new EM_Category($result);
				}
			}
		}else{
			//We assume it's either an empty array or array of search arguments to merge with defaults
			$term_args = self::get_default_search($args);		
			$results = get_terms( EM_TAXONOMY_CATEGORY, $term_args);		
		
			//If we want results directly in an array, why not have a shortcut here? We don't use this in code, so if you're using it and filter the em_categories_get hook, you may want to do this one too.
			if( !empty($args['array']) ){
				return apply_filters('em_categories_get_array', $results, $args);
			}
			
			//Make returned results EM_Category objects
			$results = (is_array($results)) ? $results:array();
			$categories = array();
			foreach ( $results as $category ){
				$categories[$category->term_id] = new EM_Category($category);
			}
		}
		self::ms_global_switch_back();	
		return apply_filters('em_categories_get', $categories, $args);
	}

	public static function output( $args ){
		global $EM_Category;
		$EM_Category_old = $EM_Category; //When looping, we can replace EM_Category global with the current event in the loop
		//get page number if passed on by request (still needs pagination enabled to have effect)
		$page_queryvar = !empty($args['page_queryvar']) ? $args['page_queryvar'] : 'pno';
		if( !array_key_exists('page',$args) && !empty($args['pagination']) && !empty($_REQUEST[$page_queryvar]) && is_numeric($_REQUEST[$page_queryvar]) ){
			$page = $args['page'] = $_REQUEST[$page_queryvar];
		}
		//Can be either an array for the get search or an array of EM_Category objects
		if( is_object(current($args)) && get_class((current($args))) == 'EM_Category' ){
			$func_args = func_get_args();
			$categories = $func_args[0];
			$args = (!empty($func_args[1])) ? $func_args[1] : array();
			$args = apply_filters('em_categories_output_args', self::get_default_search($args), $categories);
			$limit = ( !empty($args['limit']) && is_numeric($args['limit']) ) ? $args['limit']:false;
			$offset = ( !empty($args['offset']) && is_numeric($args['offset']) ) ? $args['offset']:0;
			$page = ( !empty($args['page']) && is_numeric($args['page']) ) ? $args['page']:1;
		}else{
			$args = apply_filters('em_categories_output_args', self::get_default_search($args) );
			$limit = ( !empty($args['limit']) && is_numeric($args['limit']) ) ? $args['limit']:false;
			$offset = ( !empty($args['offset']) && is_numeric($args['offset']) ) ? $args['offset']:0;
			$page = ( !empty($args['page']) && is_numeric($args['page']) ) ? $args['page']:1;
			$args['limit'] = $args['offset'] = $args['page'] = false; //we count overall categories here
			$categories = self::get( $args );
			$args['limit'] = $limit;
			$args['offset'] = $offset;
			$args['page'] = $page;
		}
		//What format shall we output this to, or use default
		$format = ( $args['format'] == '' ) ? get_option( 'dbem_categories_list_item_format' ) : $args['format'] ;
		
		$output = "";
		$categories_count = count($categories);
		$categories = apply_filters('em_categories_output_categories', $categories);
		if ( count($categories) > 0 ) {
			$category_count = 0;
			$categories_shown = 0;
			foreach ( $categories as $EM_Category ) {
				if( ($categories_shown < $limit || empty($limit)) && ($category_count >= $offset || $offset === 0) ){
					$output .= $EM_Category->output($format);
					$categories_shown++;
				}
				$category_count++;
			}
			//Add headers and footers to output
			if( $format == get_option( 'dbem_categories_list_item_format' ) ){
			    //we're using the default format, so if a custom format header or footer is supplied, we can override it, if not use the default
			    $format_header = empty($args['format_header']) ? get_option('dbem_categories_list_item_format_header') : $args['format_header'];
			    $format_footer = empty($args['format_footer']) ? get_option('dbem_categories_list_item_format_footer') : $args['format_footer'];
			}else{
			    //we're using a custom format, so if a header or footer isn't specifically supplied we assume it's blank
			    $format_header = !empty($args['format_header']) ? $args['format_header'] : '' ;
			    $format_footer = !empty($args['format_footer']) ? $args['format_footer'] : '' ;
			}
			$output =  $format_header .  $output . $format_footer;
			//Pagination (if needed/requested)
			if( !empty($args['pagination']) && !empty($limit) && $categories_count >= $limit ){
				//Show the pagination links (unless there's less than 10 events, or the custom limit)
				$output .= self::get_pagination_links($args, $categories_count);
			}
		} else {
			$output = get_option ( 'dbem_no_categories_message' );
		}
		//FIXME check if reference is ok when restoring object, due to changes in php5 v 4
		$EM_Category_old= $EM_Category;
		return apply_filters('em_categories_output', $output, $categories, $args);		
	}
	
	public static function get_pagination_links($args, $count, $search_action = 'search_cats', $default_args = array()){
		//get default args if we're in a search, supply to parent since we can't depend on late static binding until WP requires PHP 5.3 or later
		if( empty($default_args) && (!empty($args['ajax']) || !empty($_REQUEST['action']) && $_REQUEST['action'] == $search_action) ){
			$default_args = self::get_default_search();
			$default_args['limit'] = get_option('dbem_categories_default_limit');
		}
		return parent::get_pagination_links($args, $count, $search_action, $default_args);
	}

	public static function get_post_search($args = array(), $filter = false, $request = array(), $accepted_args = array()){
		//supply $accepted_args to parent argument since we can't depend on late static binding until WP requires PHP 5.3 or later
		$accepted_args = !empty($accepted_args) ? $accepted_args : array_keys(self::get_default_search());
		return apply_filters('em_tags_get_post_search', parent::get_post_search($args, $filter, $request, $accepted_args));
	}
	
	function has( $search ){
		if( is_numeric($search) ){
			foreach($this->categories as $EM_Category){
				if($EM_Category->term_id == $search) return apply_filters('em_categories_has', true, $search, $this);
			}
		}else{
			foreach($this->categories as $EM_Category){
				if($EM_Category->slug == $search || $EM_Category->name == $search ) return apply_filters('em_categories_has', true, $search, $this);
			}			
		}
		return apply_filters('em_categories_has', false, $search, $this);
	}
	
	function get_first(){
		foreach($this->categories as $EM_Category){
			return $EM_Category;
		}
		return false;
	}
	
	function get_ids(){
		$ids = array();
		foreach($this->categories as $EM_Category){
			if( !empty($EM_Category->term_id) ){
				$ids[] = $EM_Category->term_id;
			}
		}
		return $ids;	
	}
	
	/**
	 * Gets the event for this object, or a blank event if none exists
	 * @return EM_Event
	 */
	function get_event(){
		if( is_numeric($this->event_id) ){
			return em_get_event($this->event_id);
		}else{
			return new EM_Event();
		}
	}
	
	/* 
	 * Adds custom calendar search defaults
	 * @param array $array_or_defaults may be the array to override defaults
	 * @param array $array
	 * @return array
	 * @uses EM_Object#get_default_search()
	 */
	public static function get_default_search( $array_or_defaults = array(), $array = array() ){
		$defaults = array(
			//added from get_terms, so they don't get filtered out
			'orderby' => get_option('dbem_categories_default_orderby'), 'order' => get_option('dbem_categories_default_order'),
			'hide_empty' => false, 'exclude' => array(), 'exclude_tree' => array(), 'include' => array(),
			'number' => '', 'fields' => 'all', 'slug' => '', 'parent' => '',
			'hierarchical' => true, 'child_of' => 0, 'get' => '', 'name__like' => '',
			'pad_counts' => false, 'offset' => '', 'search' => '', 'cache_domain' => 'core'		
		);
		//sort out whether defaults were supplied or just the array of search values
		if( empty($array) ){
			$array = $array_or_defaults;
		}else{
			$defaults = array_merge($defaults, $array_or_defaults);
		}
		return apply_filters('em_categories_get_default_search', parent::get_default_search($defaults,$array), $array, $defaults);
	}

	//Iterator Implementation
    public function rewind(){
        reset($this->categories);
    }  
    public function current(){
        $var = current($this->categories);
        return $var;
    }  
    public function key(){
        $var = key($this->categories);
        return $var;
    }  
    public function next(){
        $var = next($this->categories);
        return $var;
    }  
    public function valid(){
        $key = key($this->categories);
        $var = ($key !== NULL && $key !== FALSE);
        return $var;
    }
}