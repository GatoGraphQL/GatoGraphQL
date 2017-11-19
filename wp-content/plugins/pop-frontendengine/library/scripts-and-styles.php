<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Scripts and styles
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// Add modified variable as to force Browser to fetch new copy of the style.css file
// add_filter("stylesheet_uri", "gd_stylesheet_uri_add_modified_var", 20);
// function gd_stylesheet_uri_add_modified_var($stylesheet) {

// 	return $stylesheet . "?version=" . pop_version();
// }


if (!is_admin()) {

	if (PoP_Frontend_ServerUtils::scripts_after_html()) {
		
		add_action( 'wp_footer', 'gd_add_scripts_header', 0);
	}
	else {

		add_action( 'wp_head', 'gd_add_scripts_header', 0);
	}
}
function gd_add_scripts_header() {
	// Code taken from /wp-admin/admin-header.php
	// Otherwise we get an error:
	// Uncaught ReferenceError: ajaxurl is not defined wp-lists.min.js?ver=4.2.2:1
?>
<script type="text/javascript">
var ajaxurl = '<?php echo admin_url( 'admin-ajax.php', 'relative' ); ?>';
</script>
<?php
}