<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * WP Default Filters
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// Remove the canonical, since we are already printing this info in the header (and we do it ourselves, as to remove the language information)
// Originally added in wp-includes/default-filters.php
remove_action( 'wp_head',             'rel_canonical'                          );

// We don't need the shortlink or the generator
remove_action( 'wp_head',             'wp_shortlink_wp_head',            10, 0 );
remove_action( 'wp_head',             'wp_generator'                           );