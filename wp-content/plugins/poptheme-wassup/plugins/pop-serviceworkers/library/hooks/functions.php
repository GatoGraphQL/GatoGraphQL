<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
// Enable ServiceWorkers to cache all TinyMCE files for the frontend
add_filter('PoP_ServiceWorkers_Hooks_TinyMCE:enable', '__return_true');