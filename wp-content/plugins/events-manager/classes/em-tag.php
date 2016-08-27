<?php
/**
 * Get an tag in a db friendly way, by checking globals and passed variables to avoid extra class instantiations
 * @param mixed $id
 * @return EM_Tag
 */
function em_get_tag($id = false) {
	global $EM_Tag;
	//check if it's not already global so we don't instantiate again
	if( is_object($EM_Tag) && get_class($EM_Tag) == 'EM_Tag' ){
		if( $EM_Tag->term_id == $id ){
			return $EM_Tag;
		}elseif( is_object($id) && $EM_Tag->term_id == $id->term_id ){
			return $EM_Tag;
		}
	}
	if( is_object($id) && get_class($id) == 'EM_Tag' ){
		return $id;
	}else{
		return new EM_Tag($id);
	}
}
class EM_Tag extends EM_Object {	
	//Taxonomy Fields
	var $id = '';
	var $term_id;
	var $name;
	var $slug;
	var $term_group;
	var $term_taxonomy_id;
	var $taxonomy;
	var $description = '';
	var $parent = 0;
	var $count;
	
	/**
	 * Gets data from POST (default), supplied array, or from the database if an ID is supplied
	 * @param $tag_data
	 * @return null
	 */
	function __construct( $tag_data = false ) {
		global $wpdb;
		//Initialize
		$tag = array();
		if( !empty($tag_data) ){
			//Load tag data
			if( is_object($tag_data) && !empty($tag_data->taxonomy) && $tag_data->taxonomy == EM_TAXONOMY_TAG ){
				$tag = $tag_data;
			}elseif( !is_numeric($tag_data) ){
				$tag = get_term_by('name', $tag_data, EM_TAXONOMY_TAG);
				if( empty($tag) ){
					$tag = get_term_by('slug', $tag_data, EM_TAXONOMY_TAG);					
				}
			}else{		
				$tag = get_term_by('id', $tag_data, EM_TAXONOMY_TAG);
			}
		}
		if( !empty($tag) ){
			foreach($tag as $key => $value){
				$this->$key = $value;
			}
			$this->id = $this->term_id; //backward compatability
		}
		do_action('em_tag',$this, $tag_data);
	}
	
	function get_url(){
		if( empty($this->link) ){
			self::ms_global_switch();
			$this->link = get_term_link($this->slug, EM_TAXONOMY_TAG);
			self::ms_global_switch_back();
			if ( is_wp_error($this->link) ) $this->link = '';
		}
		return apply_filters('em_tag_get_url', $this->link);
	}

	function get_ical_url(){
		global $wp_rewrite;
		if( !empty($wp_rewrite) && $wp_rewrite->using_permalinks() ){
			$return = trailingslashit($this->get_url()).'ical/';
		}else{
			$return = em_add_get_params($this->get_url(), array('ical'=>1));
		}
		return apply_filters('em_tag_get_ical_url', $return);
	}

	function get_rss_url(){
		global $wp_rewrite;
		if( !empty($wp_rewrite) && $wp_rewrite->using_permalinks() ){
			$return = trailingslashit($this->get_url()).'feed/';
		}else{
			$return = em_add_get_params($this->get_url(), array('feed'=>1));
		}
		return apply_filters('em_tag_get_rss_url', $return);
	}
	
	function output_single($target = 'html'){
		$format = get_option ( 'dbem_tag_page_format' );
		return apply_filters('em_tag_output_single', $this->output($format, $target), $this, $target);	
	}
	
	function output($format, $target="html") {
		preg_match_all('/\{([a-zA-Z0-9_]+)\}([^{]+)\{\/[a-zA-Z0-9_]+\}/', $format, $conditionals);
		if( count($conditionals[0]) > 0 ){
			foreach($conditionals[1] as $key => $condition){
				$format = str_replace($conditionals[0][$key], apply_filters('em_tag_output_condition', '', $condition, $conditionals[0][$key], $this), $format);
			}
		}
		$tag_string = $format;		 
	 	preg_match_all("/(#@?_?[A-Za-z0-9]+)({([a-zA-Z0-9,]+)})?/", $format, $placeholders);
	 	$replaces = array();
		foreach($placeholders[1] as $key => $result) {
			$match = true;
			$replace = '';
			$full_result = $placeholders[0][$key];
			switch( $result ){
				case '#_TAGNAME':
					$replace = $this->name;
					break;
				case '#_TAGID':
					$replace = $this->term_id;
					break;
				case '#_TAGLINK':
				case '#_TAGURL':
					$link = $this->get_url();
					$replace = ($result == '#_TAGURL') ? $link : '<a href="'.$link.'">'.esc_html($this->name).'</a>';
					break;
				case '#_TAGICALURL':
				case '#_TAGICALLINK':
					$replace = $this->get_ical_url();
					if( $result == '#_TAGICALLINK' ){
						$replace = '<a href="'.esc_url($replace).'">iCal</a>';
					}
					break;
				case '#_TAGRSSURL':
				case '#_TAGRSSLINK':
					$replace = $this->get_rss_url();
					if( $result == '#_TAGRSSLINK' ){
						$replace = '<a href="'.esc_url($replace).'">RSS</a>';
					}
					break;
				case '#_TAGNOTES':
					$replace = $this->description;
					break;
				case '#_TAGEVENTSPAST': //deprecated, erroneous documentation, left for compatability
				case '#_TAGEVENTSNEXT': //deprecated, erroneous documentation, left for compatability
				case '#_TAGEVENTSALL': //deprecated, erroneous documentation, left for compatability
				case '#_TAGPASTEVENTS':
				case '#_TAGNEXTEVENTS':
				case '#_TAGALLEVENTS':
					//convert deprecated placeholders for compatability
					$result = ($result == '#_TAGEVENTSPAST') ? '#_TAGPASTEVENTS':$result; 
					$result = ($result == '#_TAGEVENTSNEXT') ? '#_TAGNEXTEVENTS':$result;
					$result = ($result == '#_TAGEVENTSALL') ? '#_TAGALLEVENTS':$result;
					//forget it ever happened? :/
					if ($result == '#_TAGPASTEVENTS'){ $scope = 'past'; }
					elseif ( $result == '#_TAGNEXTEVENTS' ){ $scope = 'future'; }
					else{ $scope = 'all'; }
					$events_count = EM_Events::count( array('tag'=>$this->term_id, 'scope'=>$scope) );
					if ( $events_count > 0 ){
					    $args = array('tag'=>$this->term_id, 'scope'=>$scope, 'pagination'=>1, 'ajax'=>0);
					    $args['format_header'] = get_option('dbem_tag_event_list_item_header_format');
					    $args['format_footer'] = get_option('dbem_tag_event_list_item_footer_format');
					    $args['format'] = get_option('dbem_tag_event_list_item_format');
						$args['limit'] = get_option('dbem_tag_event_list_limit');
						$args['page'] = (!empty($_REQUEST['pno']) && is_numeric($_REQUEST['pno']) )? $_REQUEST['pno'] : 1;
					    $replace = EM_Events::output($args);
					} else {
						$replace = get_option('dbem_tag_no_events_message');
					}
					break;
				case '#_TAGNEXTEVENT':
					$events = EM_Events::get( array('tag'=>$this->term_id, 'scope'=>'future', 'limit'=>1, 'orderby'=>'event_start_date,event_start_time') );
					$replace = get_option('dbem_tag_no_event_message');
					foreach($events as $EM_Event){
						$replace = $EM_Event->output(get_option('dbem_tag_event_single_format'));
					}
					break;
				default:
					$replace = $full_result;
					break;
			}
			$replaces[$full_result] = apply_filters('em_tag_output_placeholder', $replace, $this, $full_result, $target);
		}
		krsort($replaces);
		foreach($replaces as $full_result => $replacement){
			$tag_string = str_replace($full_result, $replacement , $tag_string );
		}
		return apply_filters('em_tag_output', $tag_string, $this, $format, $target);	
	}
}
?>