<?php
/*
Plugin Name: PoP Engine Bootloader for WordPress
Version: 0.1
Description: Bootload the PoP Engine for WordPress
Plugin URI: https://github.com/getpop/engine-wp-bootloader/
Author: Leonardo Losoviz
*/

use PoP\Engine\AppLoader;

if (!class_exists(AppLoader::class)) {
    return;
}

/**
 * Initialize PoP Engine through the Bootloader.
 * 
 * Wait until all plugins are loaded, so that Components can decide to be resolved
 * or not based on their required plugins being active.
 */
\add_action('plugins_loaded', function(): void {
    AppLoader::initializeComponents();
    AppLoader::bootSystem();
    AppLoader::bootApplication();
});
