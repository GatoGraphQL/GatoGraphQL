<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class PoPTheme_Wassup_ServiceWorkers_Hooks_Manifest {

    function __construct() {
        
        add_filter(
            'PoP_ServiceWorkers_Manager:manifest:icons',
            array($this, 'icons')
        );
        add_filter(
            'PoP_ServiceWorkers_Manager:manifest:theme_color',
            array($this, 'color')
        );
        add_filter(
            'PoP_ServiceWorkers_Manager:manifest:background_color',
            array($this, 'color')
        );
    }

    function color($color) {

        if ($appcolor = apply_filters('PoPTheme_Wassup_ServiceWorkers_Hooks_Manifest:color', '')) {

        	return $appcolor;
        }
        
        return $color;
    }

    function icons($icons) {

        $sizes = array(
			'48x48',
			'96x96',
			'192x192',
			'256x256',
		);

		$imagename = apply_filters('PoPTheme_Wassup_ServiceWorkers_Hooks_Manifest:imagename', 'launcher-icon-');
		$path = get_stylesheet_directory_uri().'/img/';

		// // The path is relative to the website domain
		// $path = substr($path, strlen(trailingslashit(get_site_url())));
		foreach ($sizes as $size) {
			$icons[] = array(
				'src' => $path.$imagename.$size.'.png',
				'sizes' => $size,
				'type' => 'image/png',
			);
		}
        
        return $icons;
    }
}
    
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_ServiceWorkers_Hooks_Manifest();
