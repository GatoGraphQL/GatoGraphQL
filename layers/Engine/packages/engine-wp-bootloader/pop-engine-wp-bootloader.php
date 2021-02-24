<?php
/*
Plugin Name: PoP Engine Bootloader for WordPress
Version: 0.1
Description: Bootload the PoP Engine for WordPress
Plugin URI: https://github.com/getpop/engine-wp-bootloader/
Author: Leonardo Losoviz
*/

use PoP\Engine\AppLoader;

// Initialize PoP Engine through the Bootloader
AppLoader::bootSystem();
AppLoader::bootApplication();
