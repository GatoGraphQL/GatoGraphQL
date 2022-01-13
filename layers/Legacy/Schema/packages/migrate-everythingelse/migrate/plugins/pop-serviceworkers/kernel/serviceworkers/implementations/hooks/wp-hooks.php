<?php

class PoP_ServiceWorkers_Hooks_WP
{
    private $scripts;
    private $styles;
    private $dom;

    public function __construct()
    {
        $this->scripts = $this->styles = array();
        $this->doc = new DOMDocument();

        \PoP\Root\App::addFilter(
            'popcms:scriptTag',
            array($this, 'scriptLoaderTag')
        );
        \PoP\Root\App::addFilter(
            'popcms:styleTag',
            array($this, 'styleLoaderTag')
        );
        \PoP\Root\App::addFilter(
            'PoP_ServiceWorkers_Job_CacheResources:precache',
            array($this, 'getPrecacheList'),
            10,
            2
        );
        \PoP\Root\App::addFilter(
            'PoP_ServiceWorkers_Job_Fetch:exclude:full',
            array($this, 'getExcludedFullpaths'),
            10,
            2
        );
    }

    public function scriptLoaderTag($tag)
    {
        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        if (!$cmsapplicationapi->isAdminPanel()) {
            if (!empty($tag)) {
                $this->doc->loadHTML($tag);
                foreach ($this->doc->getElementsByTagName('script') as $script) {
                    if ($script->hasAttribute('src')) {
                        $this->scripts[] = $script->getAttribute('src');
                    }
                }
            }
        }

        return $tag;
    }

    public function styleLoaderTag($tag)
    {
        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        if (!$cmsapplicationapi->isAdminPanel()) {
            if (!empty($tag)) {
                $this->doc->loadHTML($tag);
                foreach ($this->doc->getElementsByTagName('link') as $link) {
                    if ($link->hasAttribute('href')) {
                        $this->styles[] = $link->getAttribute('href');
                    }
                }
            }
        }

        return $tag;
    }

    public function getPrecacheList($precache, $resourceType)
    {
        if ($resourceType == 'static') {
            // // Remove the sw-registrar.js file from the list of resources to cache!
            // global $pop_serviceworkers_manager;
            // $swRegistrar = add_query_arg('ver', POP_SERVICEWORKERS_VERSION, $pop_serviceworkers_manager->getFileurl('sw-registrar.js'));
            // $pos = array_search($swRegistrar, $this->scripts);
            // if ($pos !== false) {
            //     array_splice($this->scripts, $pos, 1);
            // }

            // File json2.min.js is not added through the $scripts list because it's lt IE 8, so then add it manually here
            // Copied from file wp-includes/script-loader.php function wp_default_scripts( &$scripts )
            global $wp_scripts;
            $suffix = SCRIPT_DEBUG ? '' : '.min';
            $this->scripts[] = add_query_arg('ver', '2015-05-03', $wp_scripts->base_url."/wp-includes/js/json2$suffix.js");

            // Needed for the thickbox: thickboxL10n['loadingAnimation'] javascript code produced in the front-end
            // Loaded in wp-includes/script-loader.php
            $precache[] = includes_url('js/thickbox/loadingAnimation.gif');

            $precache = array_merge(
                $precache,
                $this->scripts,
                $this->styles
            );
        }

        return $precache;
    }

    public function getExcludedFullpaths($excluded, $resourceType)
    {
        if ($resourceType == 'json' || $resourceType == 'html') {
            // Do not intercept access to the WP Dashboard
            $excluded[] = admin_url();
            $excluded[] = content_url();
            $excluded[] = includes_url();
        } elseif ($resourceType == 'static') {
            // Do not cache the service-worker.js file
            global $pop_serviceworkers_manager;
            $excluded[] = $pop_serviceworkers_manager->getFileurl('service-worker.js');
        }

        return $excluded;
    }
}
    
/**
 * Initialize
 */
new PoP_ServiceWorkers_Hooks_WP();
