<?php
class EM_Tags extends EM_Object implements Iterator{
	
	/**
	 * Array of EM_Tag objects for a specific event
	 * @var array
	 */
	var $tags = array();
	/**
	 * Event ID of this set of tags
	 * @var int
	 */
	var $event_id;
	/**
	 * Post ID of this set of tags
	 * @var int
	 */
	var $post_id;
	
	/**
	 * Creates an EM_Tags instance, currently accepts an EM_Event object (gets all Tags for that event) or array of any EM_Tag objects, which can be manipulated in bulk with helper functions.
	 * @param mixed $data
	 * @return null
	 */
	function __construct( $data = false ){
		global $wpdb;
		//an EM_Event object
		if( is_object($data) && get_class($data) == "EM_Event" && !empty($data->post_id) ){ //Creates a blank tags object if needed
			$this->event_id = $data->event_id;
			$this->post_id = $data->post_id;
		    if( EM_MS_GLOBAL && (get_current_blog_id() !== $data->blog_id || (!$data->blog_id && !is_main_site()) )  ){
				if( !$this->blog_id ) $this->blog_id = get_current_site()->blog_id;
		        switch_to_blog($this->blog_id);
				$results = get_the_terms( $data->post_id, EM_TAXONOMY_TAG );
				restore_current_blog();
		    }else{
				$results = get_the_terms( $data->post_id, EM_TAXONOMY_TAG );
		    }
			if( is_array($results) ){
				foreach($results as $result){
					$this->tags[$result->term_id] = new EM_Tag($result);
				}
			}
		//array of EM_Tag ids
		}elseif( is_array($data) && self::array_is_numeric($data) ){
			foreach($data as $tag_id){
				$this->tags[$tag_id] =  new EM_Tag($tag_id);
			}
		//array of EM_Tag objects
		}elseif( is_array($data) ){
			foreach( $data as $EM_Tag ){
				if( get_class($EM_Tag) == 'EM_Tag'){
					$this->tags[] = $EM_Tag;
				}
			}
		}
		do_action('em_tags', $this);
	}
	
	/* experimental, not tested! */
	function get_post(){
		self::ms_global_switch();
		$this->tags = array();
		if(!empty($_POST['event_tags']) && self::array_is_numeric($_POST['event_tags'])){
			foreach( $_POST['event_tags'] as $term ){
				$this->tags[$term] = new EM_Tag($term);
			}
		}
		self::ms_global_switch_back();
		do_action('em_tags_get_post', $this);
	}

	/* experimental, not tested! */
	function save(){
		$term_slugs = array();
		foreach($this->tags as $EM_Tag){
			/* @var $EM_Tag EM_Tag */
			if( !empty($EM_Tag->slug) ) $term_slugs[] = $EM_Tag->slug; //save of tag will soft-fail if slug is empty
		}
		if( count($term_slugs) == 0 && get_option('dbem_default_tag') > 0 ){
			$default_term = get_term_by('id',get_option('dbem_default_tag'), EM_TAXONOMY_TAG);
			if($default_term) $term_slugs[] = $default_term->slug;
		}
		if( count($term_slugs) > 0 ){
			wp_set_object_terms($this->post_id, $term_slugs, EM_TAXONOMY_TAG);
		}
		do_action('em_tags_save', $this);
	}
		
	public static function get( $args = array() ) {		
		//Quick version, we can accept an array of IDs, which is easy to retrieve
		if( self::array_is_numeric($args) ){ //Array of numbers, assume they are event IDs to retreive
			$results = get_terms( EM_TAXONOMY_TAG );
			$tags = array();
			foreach($results as $result){
				if( in_array($result->term_id, $args) ){
					$tags[$result->term_id] = new EM_Tag($result);
				}
			}
		}else{
			//We assume it's either an empty array or array of search arguments to merge with defaults
			$term_args = self::get_default_search($args);		
			$results = get_terms( EM_TAXONOMY_TAG, $term_args);		
		
			//If we want results directly in an array, why not have a shortcut here? We don't use this in code, so if you're using it and filter the em_tags_get hook, you may want to do this one too.
			if( !empty($args['array']) ){
				return apply_filters('em_tags_get_array', $results, $args);
			}
			
			//Make returned results EM_Tag objects
			$results = (is_array($results)) ? $results:array();
			$tags = array();
			foreach ( $results as $tag ){
				$tags[$tag->term_id] = new EM_Tag($tag);
			}
		}
		self::ms_global_switch_back();	
		return apply_filters('em_tags_get', $tags, $args);
	}

	public static function output( $args ){
		global $EM_Tag;
		$EM_Tag_old = $EM_Tag; //When looping, we can replace EM_Tag global with the current event in the loop
		//get page number if passed on by request (still needs pagination enabled to have effect)
		$page_queryvar = !empty($args['page_queryvar']) ? $args['page_queryvar'] : 'pno';
		if( !empty($args['pagination']) && !array_key_exists('page',$args) && !empty($_REQUEST[$page_queryvar]) && is_numeric($_REQUEST[$page_queryvar]) ){
			$page = $args['page'] = $_REQUEST[$page_queryvar];
		}
		//Can be either an array for the get search or an array of EM_Tag objects
		if( is_object(current($args)) && get_class((current($args))) == 'EM_Tag' ){
			$func_args = func_get_args();
			$tags = $func_args[0];
			$args = (!empty($func_args[1])) ? $func_args[1] : array();
			$args = apply_filters('em_tags_output_args', self::get_default_search($args), $tags);
			$limit = ( !empty($args['limit']) && is_numeric($args['limit']) ) ? $args['limit']:false;
			$offset = ( !empty($args['offset']) && is_numeric($args['offset']) ) ? $args['offset']:0;
			$page = ( !empty($args['page']) && is_numeric($args['page']) ) ? $args['page']:1;
		}else{
			$args = apply_filters('em_tags_output_args', self::get_default_search($args) );
			$limit = ( !empty($args['limit']) && is_numeric($args['limit']) ) ? $args['limit']:false;
			$offset = ( !empty($args['offset']) && is_numeric($args['offset']) ) ? $args['offset']:0;
			$page = ( !empty($args['page']) && is_numeric($args['page']) ) ? $args['page']:1;
			$args['limit'] = $args['offset'] = $args['page'] = false; //we count overall tags here
			$tags = self::get( $args );
			$args['limit'] = $limit;
			$args['offset'] = $offset;
			$args['page'] = $page;
		}
		//What format shall we output this to, or use default
		$format = ( $args['format'] == '' ) ? get_option( 'dbem_tags_list_item_format' ) : $args['format'] ;
		
		$output = "";
		$tags_count = count($tags);
		$tags = apply_filters('em_tags_output_tags', $tags);
		if ( count($tags) > 0 ) {
			$tag_count = 0;
			$tags_shown = 0;
			foreach ( $tags as $EM_Tag ) {
				if( ($tags_shown < $limit || empty($limit)) && ($tag_count >= $offset || $offset === 0) ){
					$output .= $EM_Tag->output($format);
					$tags_shown++;
				}
				$tag_count++;
			}
			//Add headers and footers to output
			if( $format == get_option( 'dbem_tags_list_item_format' ) ){
			    //we're using the default format, so if a custom format header or footer is supplied, we can override it, if not use the default
			    $format_header = empty($args['format_header']) ? get_option('dbem_tags_list_item_format_header') : $args['format_header'];
			    $format_footer = empty($args['format_footer']) ? get_option('dbem_tags_list_item_format_footer') : $args['format_footer'];
			}else{
			    //we're using a custom format, so if a header or footer isn't specifically supplied we assume it's blank
			    $format_header = !empty($args['format_header']) ? $args['format_header'] : '' ;
			    $format_footer = !empty($args['format_footer']) ? $args['format_footer'] : '' ;
			}
			$output =  $format_header .  $output . $format_footer;
			
			//Pagination (if needed/requested)
			if( !empty($args['pagination']) && !empty($limit) && $tags_count >= $limit ){
				$output .= self::get_pagination_links($args, $tags_count);
			}
		} else {
			$output = get_option ( 'dbem_no_tags_message' );
		}
		//FIXME check if reference is ok when restoring object, due to changes in php5 v 4
		$EM_Tag_old= $EM_Tag;
		return apply_filters('em_tags_output', $output, $tags, $args);		
	}
	
	public static function get_pagination_links($args, $count, $search_action = 'search_tags', $default_args = array()){
		//get default args if we're in a search, supply to parent since we can't depend on late static binding until WP requires PHP 5.3 or later
		if( empty($default_args) && (!empty($args['ajax']) || !empty($_REQUEST['action']) && $_REQUEST['action'] == $search_action) ){ 
			$default_args = self::get_default_search();
			$default_args['limit'] = get_option('dbem_tags_default_limit');
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
			foreach($this->tags as $EM_Tag){
				if($EM_Tag->term_id == $search) return apply_filters('em_tags_has', true, $search, $this);
			}
		}else{
			foreach($this->tags as $EM_Tag){
				if($EM_Tag->slug == $search || $EM_Tag->name == $search ) return apply_filters('em_tags_has', true, $search, $this);
			}			
		}
		return apply_filters('em_tags_has', false, $search, $this);
	}
	
	function get_first(){
		foreach($this->tags as $EM_Tag){
			return $EM_Tag;
		}
		return false;
	}
	
	function get_ids(){
		$ids = array();
		foreach($this->tags as $EM_Tag){
			if( !empty($EM_Tag->term_id) ){
				$ids[] = $EM_Tag->term_id;
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
	 * Adds custom tags search defaults
	 * @param array $array_or_defaults may be the array to override defaults
	 * @param array $array
	 * @return array
	 * @uses EM_Object#get_default_search()
	 */
	public static function get_default_search( $array_or_defaults = array(), $array = array() ){
		$defaults = array(
			//added from get_terms, so they don't get filtered out
			'orderby' => get_option('dbem_tags_default_orderby', 'name'), 'order' => get_option('dbem_tags_default_order', 'name'),
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
		return apply_filters('em_tags_get_default_search', parent::get_default_search($defaults,$array), $array, $defaults);
	}

	//Iterator Implementation
    public function rewind(){
        reset($this->tags);
    }  
    public function current(){
        $var = current($this->tags);
        return $var;
    }  
    public function key(){
        $var = key($this->tags);
        return $var;
    }  
    public function next(){
        $var = next($this->tags);
        return $var;
    }  
    public function valid(){
        $key = key($this->tags);
        $var = ($key !== NULL && $key !== FALSE);
        return $var;
    }
}