<?php
class WPSC_PoP_Installation
{
    public function __construct()
    {

        /**
         * Whenever there is a new software version, delete the cache
         */
        \PoP\Root\App::addAction('PoP:system-build', 'wp_cache_clear_cache');

        /**
         * Allow to override what files to ignore to cache: all the ones with checkpoint needed
         */
        // Priority 20: After the config file has been created
        \PoP\Root\App::addAction('PoP:system-generate', array($this, 'setRejectedUri'), 20);
    }

    public function setRejectedUri()
    {

        // Check if we have rejected uris, if so replace them in wp-cache-config.php
        if ($rejected_uris = array_unique(\PoP\Root\App::applyFilters('pop_wp_cache_set_rejected_uri', array()))) {
            // Add the original ones in
            $original = array('wp-.*\\\\.php', 'index\\\\.php');
            $rejected_uris = array_merge(
                $original,
                $rejected_uris
            );
            $pop_cache_rejected_uri = "array('".implode("', '", $rejected_uris)."')";
            
            // // Taken from http://z9.io/wp-super-cache-developers/
            // global $wp_cache_config_file;
            // wp_cache_replace_line('^ *\$cache_rejected_uri', "\$cache_rejected_uri = " . $pop_cache_rejected_uri . ";", $wp_cache_config_file);

            // Do it on the pop cache config file instead, so that different websites can keep different configurations
            global $pop_wp_cache_config_file;
            wp_cache_replace_line('^ *\$cache_rejected_uri', "\$cache_rejected_uri = " . $pop_cache_rejected_uri . ";", $pop_wp_cache_config_file);
        }
    }
}

/**
 * Initialization
 */
new WPSC_PoP_Installation();
