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

use GatoGraphQL\GatoGraphQL\PluginMetadata;
use GatoGraphQL\GatoGraphQL\PluginSkeleton\PluginUninstaller;

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

/**
 * The built plugin registers its scoped classes through the Scoper
 * autoloader, and only the plugin's main file is pointed at it when the
 * plugin is built. From source, that file does not exist.
 */
$gatoGraphQLAutoloadFilePath = __DIR__ . '/vendor/scoper-autoload.php';
if (!file_exists($gatoGraphQLAutoloadFilePath)) {
    $gatoGraphQLAutoloadFilePath = __DIR__ . '/vendor/autoload.php';
}
if (!file_exists($gatoGraphQLAutoloadFilePath)) {
    return;
}
require_once $gatoGraphQLAutoloadFilePath;

if (!class_exists(PluginUninstaller::class)) {
    return;
}

PluginUninstaller::uninstall(PluginMetadata::PLUGIN_NAMESPACE, basename(__DIR__));
