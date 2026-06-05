<?php
/*
Plugin Name: Gato GraphQL
Plugin URI: https://gatographql.com
GitHub Plugin URI: https://github.com/GatoGraphQL/GatoGraphQL
Description: Powerful and flexible GraphQL server for WordPress.
Version: 18.1.0-dev
Requires at least: 6.1
Requires PHP: 8.1
Author: Gato GraphQL
Author URI: https://gatographql.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
Text Domain: gatographql
Domain Path: /languages
GitHub Plugin URI: GatoGraphQL/gatographql-dist
*/

use GatoGraphQL\GatoGraphQL\Plugin;
use GatoGraphQL\GatoGraphQL\PluginApp;

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('gatographql_load_textdomain_with_fallback')) {
    /**
     * Load the .mo for the current locale into the 'gatographql' text domain,
     * falling back to a shipped variant of the same base language when the exact
     * locale's file is absent (e.g. es_AR / es_MX reuse es_ES, fr_CA reuses fr_FR).
     */
    function gatographql_load_textdomain_with_fallback(string $dir, string $prefix): void
    {
        $locale = determine_locale();
        $mofile = $dir . $prefix . $locale . '.mo';
        if (!is_readable($mofile)) {
            $base = (string) strtok($locale, '_');
            $canonical = $dir . $prefix . $base . '_' . strtoupper($base) . '.mo';
            if (is_readable($canonical)) {
                $mofile = $canonical;
            } else {
                $variants = glob($dir . $prefix . $base . '_*.mo') ?: [];
                $mofile = $variants[0] ?? $mofile;
            }
        }
        if (is_readable($mofile)) {
            load_textdomain('gatographql', $mofile);
        }
    }
}
if (!function_exists('gatographql_resolve_script_translation_file')) {
    /**
     * Mirror the .mo language fallback for JS translation packs: when the exact
     * locale's <domain>-<locale>-<md5>.json is missing, reuse a shipped variant of
     * the same base language. The md5 (script-path hash) is locale-independent, so
     * only the locale segment is swapped. Hooked on 'load_script_translation_file'.
     *
     * @param string|false $file
     * @return string|false
     */
    function gatographql_resolve_script_translation_file($file, $handle, $domain)
    {
        if ($domain !== 'gatographql' || !is_string($file) || is_readable($file)) {
            return $file;
        }
        if (!preg_match('#^(.*/gatographql-)([a-z]{2,3})(?:_[A-Za-z0-9]+)*(-[0-9a-f]+\.json)$#', $file, $m)) {
            return $file;
        }
        $canonical = $m[1] . $m[2] . '_' . strtoupper($m[2]) . $m[3];
        if (is_readable($canonical)) {
            return $canonical;
        }
        $variants = glob($m[1] . $m[2] . '_*' . $m[3]) ?: [];
        return $variants[0] ?? $file;
    }
}
add_filter('load_script_translation_file', 'gatographql_resolve_script_translation_file', 10, 3);

add_action('init', function (): void {
    gatographql_load_textdomain_with_fallback(__DIR__ . '/languages/', basename(__FILE__, '.php') . '-');
}, PHP_INT_MIN);

/**
 * Plugin's name and version.
 *
 * Use a stability suffix as supported by Composer.
 *
 * @see https://getcomposer.org/doc/articles/versions.md#stabilities
 * 
 * Important: Do not modify the formatting of this PHP code!
 * A regex will search for this exact pattern, to update the
 * version in the ReleaseWorker when deploying for PROD.
 *
 * @see src/OnDemand/Symplify/MonorepoBuilder/Release/ReleaseWorker/ConvertVersionForProdInPluginMainFileReleaseWorker.php
 *
 * @gatographql-readonly-code
 */
$pluginVersion = '18.1.0-dev';
$pluginName = 'Gato GraphQL';

/**
 * If the plugin is already registered, halt loading
 */
if (class_exists(Plugin::class)) {
    return;
}

// Validate that there is enough memory to run the plugin
require_once __DIR__ . '/includes/startup.php';
if (!\PoPIncludes\GatoGraphQL\Startup::checkGatoGraphQLMemoryRequirements($pluginName)) {
    return;
}

/**
 * Can't use Composer to load this file, as "vendor/" is loaded only
 * in the "plugins_loaded" hook, and that's too late to register
 * the capabilities.
 */
require_once __DIR__ . '/includes/capabilities.php';
require_once __DIR__ . '/includes/schema-editing-access-capabilities.php';
\PoPIncludes\GatoGraphQL\SchemaEditingAccessCapabilities::registerGatoGraphQLSchemaEditingAccessCapabilities(
    __FILE__,
    constant('GATOGRAPHQL_CAPABILITY_MANAGE_GRAPHQL_SCHEMA')
);

/**
 * The commit hash is added to the plugin version 
 * through the CI when merging the PR.
 *
 * It is required to regenerate the container when
 * testing a generated .zip plugin without modifying
 * the plugin version.
 * (Otherwise, we'd have to @purge-cache.)
 *
 * Important: Do not modify this code!
 * It will be replaced in the CI to append "#{commit hash}"
 * when generating the plugin. 
 *
 * @gatographql-readonly-code
 */
$commitHash = null;

// Load Composer’s autoloader
require_once(__DIR__ . '/vendor/autoload.php');

// Initialize the Gato GraphQL App
PluginApp::initializePlugin();

// Create and set-up the plugin instance
PluginApp::getMainPluginManager()->register(new Plugin(
    __FILE__,
    $pluginVersion,
    $pluginName,
    $commitHash
))->setup();
