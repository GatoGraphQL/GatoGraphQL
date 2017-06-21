<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Configure the RSS Feed
 *
 * ---------------------------------------------------------------------------------------------------------------*/

/**---------------------------------------------------------------------------------------------------------------
 * Add the Featured Image to the feed
 * Taken from http://code.tutsplus.com/tutorials/extending-the-default-wordpress-rss-feed--wp-27935
 *
 * Mailchimp Documentation:
 * - http://kb.mailchimp.com/merge-tags/rss-blog/add-a-blog-post-to-any-campaign
 * - http://kb.mailchimp.com/merge-tags/rss-blog/rss-merge-tags
 * ---------------------------------------------------------------------------------------------------------------*/

add_action( 'rss2_ns', 'gd_rss_namespace' );
function gd_rss_namespace() {
    echo 'xmlns:media="http://search.yahoo.com/mrss/"
    xmlns:georss="http://www.georss.org/georss"';
}

add_action( 'rss2_item', 'gd_rss_featured_image' );
function gd_rss_featured_image() {
    
	$vars = GD_TemplateManager_Utils::get_vars();
    $post = $vars['global-state']['post']/*global $post*/;
   	gd_rss_print_featured_image($post->ID);
}
function gd_rss_print_featured_image($post_id) {

	if ($featuredimage_id = gd_get_thumb_id($post_id)) {
		$featuredimage = get_post($featuredimage_id);
		
		// Allow to set the image width in the URL: Needed for using the rss merge tag *|RSSITEM:IMAGE|* in Mailchimp,
		// since it does not allow to resize the image
		$img_attr = apply_filters('gd_rss_print_featured_image:img_attr', wp_get_attachment_image_src($featuredimage_id, 'thumb-md'), $featuredimage_id);
		?>
		<media:content url="<?php echo $img_attr[0] ?>" type="<?php echo $featuredimage->post_mime_type; ?>" medium="image" width="<?php echo $img_attr[1] ?>" height="<?php echo $img_attr[2] ?>">
			<media:description type="plain"><![CDATA[<?php echo $featuredimage->post_title; ?>]]></media:description>
		</media:content>
		<?php
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Standard
 * ---------------------------------------------------------------------------------------------------------------*/

// function gd_categories_not_to_include_in_rss() {

// 	return apply_filters('gd_categories_not_to_include_in_rss', array());
// }
// function gd_posts_types_to_include_in_rss() {

// 	return apply_filters('gd_posts_types_to_include_in_rss', array('post'));
// }

add_filter('pre_get_posts','gd_rss_filter');
function gd_rss_filter($query) {

	 if ($query->is_feed) {
	 
		// Comment Leo 27/08/2016: Instead of saying what categories are not in, we say what taxonomies are in
		// $category_not_in = gd_categories_not_to_include_in_rss();
		// $post_types = gd_posts_types_to_include_in_rss();
		
		// $query->set('category__not_in', $category_not_in);
        // $query->set('post_type', $post_types);

	 	$query->set('post_type', gd_dataload_posttypes());
	 	$tax_query = array_merge(
			array(
				'relation' => 'OR'
			),
			gd_dataload_allcontent_taxquery_items()
		);
		$query->set('tax_query', $tax_query);
	 }
	return $query;
} 
