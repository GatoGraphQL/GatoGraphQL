<?php
class EM_Location_Post {
	public static function init(){
		//Front Side Modifiers
		if( !is_admin() ){
			//override single page with formats? 
			add_filter('the_content', array('EM_Location_Post','the_content'));
			//override excerpts?
			if( get_option('dbem_cp_locations_excerpt_formats') ){
			    add_filter('the_excerpt', array('EM_Location_Post','the_excerpt'));
			}
			//display as page or other template?
			if( get_option('dbem_cp_locations_template') ){
				add_filter('single_template',array('EM_Location_Post','single_template'));
			}
			//add classes to body and post_class()
			if( get_option('dbem_cp_locations_post_class') ){
			    add_filter('post_class', array('EM_Location_Post','post_class'), 10, 3);
			}
			if( get_option('dbem_cp_locations_body_class') ){
			    add_filter('body_class', array('EM_Location_Post','body_class'), 10, 3);
			}
		}
		add_action('parse_query', array('EM_Location_Post','parse_query'));
	}	
	
	/**
	 * Overrides the default post format of a location and can display a location as a page, which uses the page.php template.
	 * @param string $template
	 * @return string
	 */
	public static function single_template($template){
		global $post;
		if( !locate_template('single-'.EM_POST_TYPE_LOCATION.'.php') && $post->post_type == EM_POST_TYPE_LOCATION ){
			//do we have a default template to choose for events?
			if( get_option('dbem_cp_locations_template') == 'page' ){
				$post_templates = array('page.php','index.php');
			}else{
			    $post_templates = array(get_option('dbem_cp_locations_template'));
			}
			if( !empty($post_templates) ){
			    $post_template = locate_template($post_templates,false);
			    if( !empty($post_template) ) $template = $post_template;
			}
		}
		return $template;
	}
	
	public static function post_class( $classes, $class, $post_id ){
	    $post = get_post($post_id);
	    if( $post->post_type == EM_POST_TYPE_LOCATION ){
	        foreach( explode(' ', get_option('dbem_cp_locations_post_class')) as $class ){
	            $classes[] = esc_attr($class);
	        }
	    }
	    return $classes;
	}
	
	public static function body_class( $classes ){
	    if( em_is_location_page() ){
	        foreach( explode(' ', get_option('dbem_cp_locations_body_class')) as $class ){
	            $classes[] = esc_attr($class);
	        }
	    }
	    return $classes;
	}
	
	/**
	 * Overrides the_excerpt if this is an location post type
	 */
	public static function the_excerpt($content){
		global $post;
		if( $post->post_type == EM_POST_TYPE_LOCATION ){
			$EM_Location = em_get_location($post);
			$output = !empty($EM_Location->post_excerpt) ? get_option('dbem_location_excerpt_format'):get_option('dbem_location_excerpt_alt_format');
			$content = $EM_Location->output($output);
		}
		return $content;
	}
	
	public static function the_content( $content ){
		global $post, $EM_Location;
		if( $post->post_type == EM_POST_TYPE_LOCATION ){
			if( is_archive() || is_search() ){
				if( get_option('dbem_cp_locations_archive_formats') ){
					$EM_Location = em_get_location($post);
					$content = $EM_Location->output(get_option('dbem_location_list_item_format'));
				}
			}else{
				if( get_option('dbem_cp_locations_formats') && !post_password_required() ){
					$EM_Location = em_get_location($post);
					ob_start();
					em_locate_template('templates/location-single.php',true);
					$content = ob_get_clean();
				}
			}
		}
		return $content;
	}
	
	public static function parse_query(){
	    global $wp_query;
		if( !empty($wp_query->query_vars['post_type']) && $wp_query->query_vars['post_type'] == EM_POST_TYPE_LOCATION ){
			if( is_admin() ){
				$wp_query->query_vars['orderby'] = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby']:'title';
				$wp_query->query_vars['order'] = (!empty($_REQUEST['order'])) ? $_REQUEST['order']:'ASC';
			}else{
			  	if( get_option('dbem_locations_default_archive_orderby') == 'title'){
			  		$wp_query->query_vars['orderby'] = 'title';
			  	}else{
				  	$wp_query->query_vars['orderby'] = 'meta_value_num';
				  	$wp_query->query_vars['meta_key'] = get_option('dbem_locations_default_archive_orderby','_location_country');	  		
			  	}
				$wp_query->query_vars['order'] = get_option('dbem_locations_default_archive_order','ASC');
			}
		}
	}
}
EM_Location_Post::init();