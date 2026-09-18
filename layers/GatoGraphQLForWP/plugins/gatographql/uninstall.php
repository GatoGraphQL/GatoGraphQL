<?php

/**
 * Remove the data the plugin has stored on the site, if the user asked for
 * that under Settings => Plugin Management => Uninstall.
 *
 * WordPress runs this file when the plugin is deleted, with WordPress loaded
 * but the plugin not bootstrapped. Only the autoloader is loaded here, and
 * never the plugin itself: the work is driven by the single option the plugin
 * records everything it installed under.
 *
 * @see \GatoGraphQL\GatoGraphQL\PluginSkeleton\PluginUninstaller
 */

declare(strict_types=1);

use GatoGraphQL\GatoGraphQL\PluginSkeleton\PluginUninstaller;

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

$gatoGraphQLAutoloadFilePath = __DIR__ . '/vendor/autoload.php';
if (!file_exists($gatoGraphQLAutoloadFilePath)) {
    return;
}
require_once $gatoGraphQLAutoloadFilePath;

if (!class_exists(PluginUninstaller::class)) {
    return;
}

PluginUninstaller::uninstall();
