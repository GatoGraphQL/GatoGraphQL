<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
// Enable ServiceWorkers to cache all TinyMCE files for the frontend
add_filter('PoP_ServiceWorkers_Hooks_TinyMCE:enable', '__return_true');

// The Wassup Theme color can also be used for the service workers
add_filter('PoPTheme_Wassup_ServiceWorkers_Hooks_Manifest:color', 'gd_get_theme_color');