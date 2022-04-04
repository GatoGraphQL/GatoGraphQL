<?php
class WPSC_PoP_Custer_Installation
{
    public function __construct()
    {

        /**
          * Load a customized config for each website
          */
        \PoP\Root\App::addAction('PoP:system-generate', $this->addCustomConfig(...));
        
        /**
         * Add $_SERVER["SERVER_NAME"] to the cache path, so we can have several websites hosted all together yet each having its own CACHE folder
         */
        \PoP\Root\App::addAction('PoP:system-build', $this->setCachePath(...));
        
        /**
         * Generate the .htaccess rules for the Gzip compression inside the cluster folder
         */
        \PoP\Root\App::addAction('PoP:system-generate', $this->gzipRules(...));
    }

    public function addCustomConfig()
    {

        // Do it on the pop cache config file instead, so that different websites can keep different configurations
        $pop_wp_cache_config_folder = WP_CONTENT_DIR . "/pop-cache-config/".$_SERVER["SERVER_NAME"];
        global $pop_wp_cache_config_file;
        $pop_wp_cache_config_file = $pop_wp_cache_config_folder."/wp-cache-config.php";

        // Make sure the folder exists
        if (!file_exists($pop_wp_cache_config_folder)) {
            // Create the settings folder
            @mkdir($pop_wp_cache_config_folder, 0755, true);
        }

        if (!file_exists($pop_wp_cache_config_file)) {
            // Open the file, write content and close it
            $handle = fopen($pop_wp_cache_config_file, "w");
            fputs($handle, "<?php\n");
            fputs($handle, "?>");
            fclose($handle);
        }
    }

    public function setCachePath()
    {
        global $wp_cache_config_file;

        // Copied from wp-content/plugins/wp-super-cache/wp-cache.php
        $pop_cache_path = WP_CONTENT_DIR . '/cache/';
            
        // Add .$_SERVER['SERVER_NAME'] inside the generated line. Not using POP_WEBSITE so that the variable can also be used in .htaccess, see below
        // wp_cache_replace_line('^ *\$cache_path', "\$cache_path = '" . $pop_cache_path . "'.POP_WEBSITE;", $wp_cache_config_file);
        wp_cache_replace_line('^ *\$cache_path', "\$cache_path = '" . $pop_cache_path . "'".'.$_SERVER["SERVER_NAME"];', $wp_cache_config_file);

        // // Do it in the custom file instead
        // $pop_wp_cache_config_folder = WP_CONTENT_DIR . "/pop-cache-config/".$_SERVER["SERVER_NAME"];
        // $pop_wp_cache_config_file = $pop_wp_cache_config_folder."/wp-cache-config.php";
        // wp_cache_replace_line('^ *\$cache_path', "\$cache_path = '" . $pop_cache_path . "'".'.$_SERVER["SERVER_NAME"];', $pop_wp_cache_config_file);
    }

    // Code copied from file wp-content/plugins/wp-super-cache/wp-cache.php function wscModRewrite()
    public function gzipRules()
    {

        // function get_home_path() is needed, it's originally not loaded since it belongs to the WP Admin
        /**
         * WordPress Administration File API
        */
        include_once ABSPATH . 'wp-admin/includes/file.php';

        // Neded for function extractFromMarkers()
        /**
         * WordPress Misc Administration API
        */
        include_once ABSPATH . 'wp-admin/includes/misc.php';

        global $cache_path, $wp_cache_mod_rewrite;

        if (defined('WPSC_DISABLE_HTACCESS_UPDATE')) {
            return false;
        }

        if (!$wp_cache_mod_rewrite) {
            return false;
        }

        // $pop_cache_path = WP_CONTENT_DIR . '/cache/' . $_SERVER["SERVER_NAME"];

        // Create the cache folder. Using the code like this instead of re-using WP Super Cache's code, because that one expects the folder cache to
        // have only 1 level so it doesn't have the mkdir true parameter to make it recursive, and so it fails
        if (!file_exists($cache_path)) {
            @mkdir($cache_path, 0755, true);
        }

        extract(wpsc_get_htaccess_info());
        $gziprules = insert_with_markers($cache_path . '.htaccess', 'supercache', explode("\n", $gziprules));
    }
}

/**
 * Initialization
 */
new WPSC_PoP_Custer_Installation();
