<?php
/*
Plugin Name: PoP Engine Bootloader for WordPress
Version: 0.1
Description: Bootload the PoP Engine for WordPress
Plugin URI: https://github.com/getpop/engine-wp-bootloader/
Author: Gato GraphQL
*/

use PoP\RootWP\AppLoader;
use PoP\RootWP\StateManagers\HookManager;
use PoP\Root\App;
use PoP\Root\AppThread;

if (!class_exists(App::class)) {
    return;
}

/**
 * Initialize PoP Engine through the Bootloader.
 * 
 * Wait until all plugins are loaded, so that Components can decide to be resolved
 * or not based on their required plugins being active.
 */
\add_action('after_setup_theme', function(): void {
    App::setAppThread(new AppThread());
    App::initialize(
        new AppLoader(),
        new HookManager()
    );
    $appLoader = App::getAppLoader();
    $appLoader->initializeModules();
    $appLoader->bootSystem();
    $appLoader->bootApplication();
    $appLoader->bootApplicationModules();
});
