<?php

class PoP_ServiceWorkers_Hooks_TinyMCE
{
    private $content_css;
    private $externalPlugins;
    private $plugins;
    private $others;

    public function __construct()
    {
        $this->content_css = $this->externalPlugins = $this->plugins = $this->others = array();

        // Execute last one
        \PoP\Root\App::addFilter(
            'teeny_mce_before_init',
            array($this, 'storeTinymceResources'),
            PHP_INT_MAX,
            1
        );
        \PoP\Root\App::addFilter(
            'tiny_mce_before_init',
            array($this, 'storeTinymceResources'),
            PHP_INT_MAX,
            1
        );
        
        \PoP\Root\App::addFilter(
            'PoP_ServiceWorkers_Job_CacheResources:precache',
            array($this, 'getPrecacheList'),
            1000,
            2
        );
    }

    protected function enable()
    {
        return \PoP\Root\App::applyFilters('PoP_ServiceWorkers_Hooks_TinyMCE:enable', false);
    }

    public function storeTinymceResources($mceInit)
    {

        // Code copied from wp-includes/class-wp-editor.php function editorJs()
        // global $wp_version, $tinymce_version;
        // $version = 'ver=' . $tinymce_version;
        $suffix = SCRIPT_DEBUG ? '' : '.min';
        // $mce_suffix = false !== strpos( $wp_version, '-src' ) ? '' : '.min';
        $baseurl = includes_url('js/tinymce');
        $cache_suffix = $mceInit['cache_suffix'];

        // Collect all files needed by the tinymce
        if ($content_css = $mceInit['content_css']) {
            // $content_css contains file wp-includes/css/dashicons.min.css?ver=4.6.1, which is also included somewhere else in the code
            // Because of that, doing array_merge() changes the array without indexes, to an object with indexes
            // So then, add these resources at the end
            foreach (explode(',', $content_css) as $content_css_item) {
                // The $cache_suffix is added in runtime, it can be safely added already
                // Eg: wp-includes/css/dashicons.min.css?ver=4.6.1&wp-mce-4401-20160726
                $this->content_css[] = $content_css_item.'&'.$cache_suffix;
            }
        }
        if ($externalPlugins = $mceInit['externalPlugins']) {
            // externalPlugins has been applied wp_json_encode, so undo that
            if ($externalPlugins = json_decode($externalPlugins, true)) {
                foreach ($externalPlugins as $plugin) {
                    $this->externalPlugins[] = "{$plugin}?{$cache_suffix}";
                }
                // $this->externalPlugins = array_values($externalPlugins);
            }
        }
        if ($plugins = $mceInit['plugins']) {
            if ($plugins = explode(',', $plugins)) {
                // These URLs are generated on runtime in TinyMCE, without a $version
                foreach ($plugins as $plugin) {
                    // $this->plugins[] = "{$baseurl}/plugins/{$plugin}/plugin{$suffix}.js?$version";
                    $this->plugins[] = "{$baseurl}/plugins/{$plugin}/plugin{$suffix}.js?{$cache_suffix}";
                }

                if (in_array('wpembed', $plugins)) {
                    // Reference to file wp-embed.js, without any parameter, is hardcoded inside file wp-includes/js/tinymce/plugins/wpembed/plugin.min.js!!!
                    $this->others[] = includes_url('js')."/wp-embed.js";
                }
            }
        }
        if ($skin = $mceInit['skin']) {
            // Must produce: wp-includes/js/tinymce/skins/lightgray/content.min.css?wp-mce-4401-20160726
            $this->others[] = "{$baseurl}/skins/{$skin}/content{$suffix}.css?{$cache_suffix}";
            $this->others[] = "{$baseurl}/skins/{$skin}/skin{$suffix}.css?{$cache_suffix}";
            // Must produce: wp-includes/js/tinymce/skins/lightgray/fonts/tinymce.woff
            $this->others[] = "{$baseurl}/skins/{$skin}/fonts/tinymce.woff";
        }
        if ($theme = $mceInit['theme']) {
            // Must produce: wp-includes/js/tinymce/themes/modern/theme.min.js?wp-mce-4401-20160726
            $this->others[] = "{$baseurl}/themes/{$theme}/theme{$suffix}.js?{$cache_suffix}";
        }

        return $mceInit;
    }

    public function getPrecacheList($precache, $resourceType)
    {

        // This must be done only if a tinyMCE has been added to the front-end on a cached page
        // So this functionality is implemented here, but the flag must still be enabled in PoPTheme Wassup, etc
        if ($this->enable()) {
            if ($resourceType == 'static') {
                // Code copied from wp-includes/class-wp-editor.php function editorJs()
                global $wp_version, $tinymce_version;
                $version = 'ver=' . $tinymce_version;
                $suffix = SCRIPT_DEBUG ? '' : '.min';
                $mce_suffix = false !== strpos($wp_version, '-src') ? '' : '.min';
                $baseurl = includes_url('js/tinymce');

                $precache[] = "{$baseurl}/tinymce{$mce_suffix}.js?$version";
                $precache[] = "{$baseurl}/plugins/compat3x/plugin{$suffix}.js?$version";
                $precache[] = "{$baseurl}/langs/wp-langs-en.js?$version";

                // In addition, add all the files in the tinymce plugins folder, since these will be needed during runtime when initializing the tinymce textarea
                $precache = array_merge(
                    $precache,
                    $this->content_css,
                    $this->externalPlugins,
                    $this->plugins,
                    $this->others
                );

                // Comment Leo: actually, since adding the $cache_suffix to all $content_css resources, then the dashicons will not be the same,
                // so it's then ok doing array_merge, the one produced here will be:
                // wp-includes/css/dashicons.min.css?ver=4.6.1&wp-mce-4401-20160726
                // // $content_css contains file wp-includes/css/dashicons.min.css?ver=4.6.1, which is also included somewhere else in the code
                // // Because of that, doing array_merge() changes the array without indexes, to an object with indexes
                // // So then, add these resources at the end
                // foreach ($this->content_css as $resource) {
                //     if (!in_array($resource, $precache)) {
                //         $precache[] = $resource;
                //     }
                // }
            }
        }

        return $precache;
    }
}
    
/**
 * Initialize
 */
new PoP_ServiceWorkers_Hooks_TinyMCE();
