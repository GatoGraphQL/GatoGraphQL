<?php
/* WARNING!!! (2013-07-10) We intend to add a few more fields into this search form over the coming weeks/months. 
 * Overriding shouldn't hinder functionality at all but these new search options won't appear on your form! 
 */ 
/* 
 * By modifying this in your theme folder within plugins/events-manager/templates/events-search.php, you can change the way the search form will look.
 * To ensure compatability, it is recommended you maintain class, id and form name attributes, unless you now what you're doing. 
 * You also have an $args array available to you with search options passed on by your EM settings or shortcode
 */
$args = !empty($args) ? $args:array(); /* @var $args array */
?>
<div class="em-search-wrapper">
<div class="em-events-search em-search <?php if( !empty($args['main_classes']) ) echo esc_attr(implode(' ', $args['main_classes'])); ?>">
	<form action="<?php echo !empty($args['search_url']) ? esc_url($args['search_url']) : EM_URI; ?>" method="post" class="em-events-search-form em-search-form">
		<input type="hidden" name="action" value="<?php echo esc_attr($args['search_action']); ?>" />
		<?php if( $args['show_main'] ): //show the 'main' search form ?>
		<div class="em-search-main">
			<?php do_action('em_template_events_search_form_header'); //hook in here to add extra fields, text etc. ?>
			<?php 
			//search text
			if( !empty($args['search_term']) ) em_locate_template('templates/search/search.php',true,array('args'=>$args));
			if( !empty($args['search_geo']) ) em_locate_template('templates/search/geo.php',true,array('args'=>$args));
			?>
			<?php if( !empty($args['css']) ) : //show the button here if we're using the default styling, if you still want to use this and use custom CSS, then you have to override our rules ?>
			<button type="submit" class="em-search-submit loading">
				<?php //before you ask, this hack is necessary thanks to stupid IE7 ?>
				<!--[if IE 7]><span><![endif]-->
				<img src="<?php echo EM_DIR_URI; ?>includes/images/search-mag.png" />
				<!--[if IE 7]></span><![endif]-->
			</button>
			<?php endif; ?>
		</div>
		<?php endif; ?>
		<?php if( !empty($args['show_advanced']) ): //show advanced fields, collapesed if the main form is shown, inline if not ?>
		<div class="em-search-advanced" <?php if( !empty($args['advanced_hidden']) ) echo 'style="display:none"'; ?>>
			<?php
			//date range (scope)
			if( !empty($args['search_scope']) ) em_locate_template('templates/search/scope.php',true,array('args'=>$args));
			//categories
			if( get_option('dbem_categories_enabled') && !empty($args['search_categories']) ) em_locate_template('templates/search/categories.php',true,array('args'=>$args));
			//Location data
			em_locate_template('templates/search/location.php',true, array('args'=>$args));
			if( !empty($args['search_geo_units']) ) em_locate_template('templates/search/geo-units.php',true, array('args'=>$args));
			?>
			<?php do_action('em_template_events_search_form_footer'); //hook in here to add extra fields, text etc. ?>
			<?php if( !$args['show_main'] || empty($args['css']) ): //show button if it wasn't shown further up ?>
			<input type="submit" value="<?php echo esc_attr($args['search_button']); ?>" class="em-search-submit" />
			<?php endif; ?>
		</div>
		<?php endif; ?>
		<?php if( !empty($args['advanced_hidden']) && !empty($args['show_advanced']) ): //show the advanced search toggle if advanced fields are collapsed ?>
		<div class="em-search-options">
			<a href="#" class="em-toggle" rel=".em-search-advanced:.em-search-form">
				<span class="hide-advanced" style="display:none;"><?php echo esc_html($args['search_text_hide']); ?></span>
				<span class="show-advanced"><?php echo esc_html($args['search_text_show']); ?></span>
			</a>
		</div>
		<?php endif; ?>
		<?php if( (empty($args['show_advanced']) || empty($args['search_countries'])) && !empty($args['country']) ): //show country in hidden field for geo searching ?>
		<input type="hidden" name="country" value="<?php echo esc_attr($args['country']) ?>" />
		<?php endif; ?>
		<?php if( empty($args['show_advanced']) || empty($args['search_geo_units']) ): //show country in hidden field for geo searching ?>
		    <?php if( !empty($args['near_distance']) ) : ?><input name="near_distance" type="hidden" value="<?php echo $args['near_distance']; ?>" /><?php endif; ?>
		    <?php if( !empty($args['near_unit']) ) : ?><input name="near_unit" type="hidden" value="<?php echo $args['near_unit']; ?>" /><?php endif; ?>
		<?php endif; ?>
	</form>
</div>
<?php if( !empty($args['ajax']) ): ?><div class='em-search-ajax'></div><?php endif; ?>
</div>