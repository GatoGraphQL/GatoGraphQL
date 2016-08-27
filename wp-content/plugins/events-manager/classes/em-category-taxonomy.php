<?php
class EM_Category_Taxonomy{
	public static function init(){
		if( !is_admin() ){
			add_filter('taxonomy_template', array('EM_Category_Taxonomy','template'), 99);
			add_filter('parse_query', array('EM_Category_Taxonomy','parse_query'));
		}
	}
	/**
	 * Overrides archive pages e.g. locations, events, event categories, event tags based on user settings
	 * @param string $template
	 * @return string
	 */
	public static function template($template = ''){
		global $wp_query, $EM_Category, $em_category_id, $post;
		if( is_tax(EM_TAXONOMY_CATEGORY) && !locate_template('taxonomy-'.EM_TAXONOMY_CATEGORY.'.php') && get_option('dbem_cp_categories_formats', true) ){
			$EM_Category = em_get_category($wp_query->queried_object->term_id);
			if( get_option('dbem_categories_page') ){
			    //less chance for things to go wrong with themes etc. so just reset the WP_Query to think it's a page rather than taxonomy
				$wp_query = new WP_Query(array('page_id'=> get_option('dbem_categories_page')));
				$wp_query->queried_object = $wp_query->post;
				$wp_query->queried_object_id = $wp_query->post->ID;
				$wp_query->post->post_title = $wp_query->posts[0]->post_title = $wp_query->queried_object->post_title = $EM_Category->output(get_option('dbem_category_page_title_format'));
				if( !function_exists('yoast_breadcrumb') ){ //not needed by WP SEO Breadcrumbs
					$wp_query->post->post_parent = $wp_query->posts[0]->post_parent = $wp_query->queried_object->post_parent = $EM_Category->output(get_option('dbem_categories_page'));
				}
				$post = $wp_query->post;
			}else{
			    //we don't have a categories page, so we create a fake page
			    $wp_query->posts = array();
			    $wp_query->posts[0] = new stdClass();
			    $wp_query->posts[0]->post_title = $wp_query->queried_object->post_title = $EM_Category->output(get_option('dbem_category_page_title_format'));
			    $post_array = array('ID', 'post_author', 'post_date','post_date_gmt','post_content','post_excerpt','post_status','comment_status','ping_status','post_password','post_name','to_ping','pinged','post_modified','post_modified_gmt','post_content_filtered','post_parent','guid','menu_order','post_type','post_mime_type','comment_count','filter');
			    foreach($post_array as $post_array_item){
			    	$wp_query->posts[0]->$post_array_item = '';
			    }
			    $wp_query->post = $wp_query->posts[0];
			    $wp_query->post_count = 1;
			    $wp_query->found_posts = 1;
			    $wp_query->max_num_pages = 1;
			    //tweak flags for determining page type
			    $wp_query->is_tax = 0;
			    $wp_query->is_page = 1;
			    $wp_query->is_single = 0;
			    $wp_query->is_singular = 1;
			    $wp_query->is_archive = 0;
			}
			remove_filter('the_content', 'em_content'); //one less filter
			add_filter('the_content', array('EM_Category_Taxonomy','the_content')); //come in slightly early and consider other plugins
			add_filter('wpseo_breadcrumb_links',array('EM_Category_Taxonomy','wpseo_breadcrumb_links')); //for Yoast WP SEO
			$wp_query->em_category_id = $em_category_id = $EM_Category->term_id; //we assign $em_category_id just in case other themes/plugins do something out of the ordinary to WP_Query
			$template = locate_template(array('page.php','index.php'),false); //category becomes a page
			do_action('em_category_taxonomy_template');
		}
		return $template;
	}
	
	public static function the_content($content){
		global $wp_query, $EM_Category, $post, $em_category_id;
		$is_categories_page = $post->ID == get_option('dbem_categories_page');
		$category_flag = (!empty($wp_query->em_category_id) || !empty($em_category_id));
		if( ($is_categories_page && $category_flag) || (empty($post->ID) && $category_flag) ){
			$EM_Category = empty($wp_query->em_category_id) ? em_get_category($em_category_id):em_get_category($wp_query->em_category_id);
			ob_start();
			em_locate_template('templates/category-single.php',true);
			return ob_get_clean();
		}
		return $content;
	}
	
	public static function parse_query( ){
	    global $wp_query, $post;
		if( is_tax(EM_TAXONOMY_CATEGORY) ){
			//Scope is future
			$today = strtotime(date('Y-m-d', current_time('timestamp')));
			if( get_option('dbem_events_current_are_past') ){
				$wp_query->query_vars['meta_query'][] = array( 'key' => '_start_ts', 'value' => $today, 'compare' => '>=' );
			}else{
				$wp_query->query_vars['meta_query'][] = array( 'key' => '_end_ts', 'value' => $today, 'compare' => '>=' );
			}
		  	if( get_option('dbem_categories_default_archive_orderby') == 'title'){
		  		$wp_query->query_vars['orderby'] = 'title';
		  	}else{
			  	$wp_query->query_vars['orderby'] = 'meta_value_num';
			  	$wp_query->query_vars['meta_key'] = get_option('dbem_categories_default_archive_orderby','_start_ts');
		  	}
			$wp_query->query_vars['order'] = get_option('dbem_categories_default_archive_order','ASC');
		}elseif( !empty($wp_query->em_category_id) ){
		    $post = $wp_query->post;
		}
	}
	
	public static function wpseo_breadcrumb_links( $links ){
	    global $wp_query;
	    array_pop($links);
	    if( get_option('dbem_categories_page') ){
		    $links[] = array('id'=> get_option('dbem_categories_page'));
	    }
	    $links[] = array('text'=> $wp_query->posts[0]->post_title);
	    return $links;
	}
}
EM_Category_Taxonomy::init();

/**
 * Create an array of Categories. Copied from Walker_CategoryDropdown, but makes it possible for the selected argument to be an array.
 *
 * @package WordPress
 * @since 2.1.0
 * @uses Walker
 */
class EM_Walker_Category extends Walker {
	/**
	 * @see Walker::$tree_type
	 * @since 2.1.0
	 * @var string
	 */
	var $tree_type = 'event-category';

	/**
	 * @see Walker::$db_fields
	 * @since 2.1.0
	 * @todo Decouple this
	 * @var array
	 */
	var $db_fields = array ('parent' => 'parent', 'id' => 'term_id');

	function __construct(){ 
		$tree_type = EM_TAXONOMY_CATEGORY;
	}
	
	/**
	 * @see Walker::start_el()
	 */
	function start_el( &$output, $object, $depth = 0, $args = array(), $current_object_id = 0 ) {
		$pad = str_repeat('&nbsp;', $depth * 3);
		$cat_name = $object->name;
		$name = !empty($args['name']) ? $args['name']:'event_categories[]';
		$output .= !empty($args['before']) ? $args['after']:'';
		$output .= $pad."<input type=\"checkbox\" name=\"$name\" class=\"level-$depth\" value=\"".$object->term_id."\"";
		if ( (is_array($args['selected']) && in_array($object->term_id, $args['selected'])) || ($object->term_id == $args['selected']) )
			$output .= ' checked="checked"';
		$output .= ' /> ';
		$output .= $cat_name;
		$output .= !empty($args['after']) ? $args['after']:'<br />';
	}
}

/**
 * Create an array of Categories. Copied from Walker_CategoryDropdown, but makes it possible for the selected argument to be an array.
 *
 * @package WordPress
 * @since 2.1.0
 * @uses Walker
 */
class EM_Walker_CategoryMultiselect extends EM_Walker_Category {
	/**
	 * @see Walker::start_el()
	 */
	function start_el( &$output, $object, $depth = 0, $args = array(), $current_object_id = 0 ) {
		$pad = str_repeat('&nbsp;', $depth * 3);
		$cat_name = $object->name;
		$output .= "\t<option class=\"level-$depth\" value=\"".$object->term_id."\"";
		if ( (is_array($args['selected']) && in_array($object->term_id, $args['selected'])) || ($object->term_id == $args['selected']) )
			$output .= ' selected="selected"';
		$output .= '>';
		$output .= $pad.$cat_name;
		$output .= "</option>\n";
	}
}