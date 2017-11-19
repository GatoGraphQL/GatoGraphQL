<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class PoP_ServiceWorkers_Job_SW extends PoP_ServiceWorkers_Job {

    public function get_sw_js_path() {
        
        return POP_SERVICEWORKERS_ASSETS_DIR.'/js/jobs/sw.js';
    }
    
    // public function get_sw_js_filename() {
        
    //     return 'sw.js';
    // }
    
    // public function get_sw_js_dir() {
        
    //     return POP_SERVICEWORKERS_ASSETS_DIR.'/js/jobs';
    // }

    public function get_dependencies() {

        return array(
            'localforage' => POP_SERVICEWORKERS_ASSETS_DIR.'/js/dependencies/localforage.1.4.3.min.js',
            'utils' => POP_SERVICEWORKERS_ASSETS_DIR.'/js/jobs/lib/utils.js',
        );
    }

	public function get_sw_configuration() {
        
        $configuration = parent::get_sw_configuration();

        // Add a string before the version, since starting with a number makes trouble
        $configuration['$version'] = 'PoP-'.pop_version();
        $configuration['$homeDomain'] = get_site_url();
        // $configuration['$offlineImage'] = $this->get_offline_image();
        // $configuration['$offlinePages'] = $this->get_offline_pages();
        $configuration['$appshellPages'] = $this->get_appshell_pages();
        $configuration['$appshellPrecachedParams'] = array(
            'theme' => GD_URLPARAM_THEME,
            'thememode' => GD_URLPARAM_THEMEMODE,
        );
        $configuration['$appshellFromServerParams'] = array(
            GD_URLPARAM_THEMESTYLE,
            GD_URLPARAM_FORMAT, // Initially, this is a proxy for GD_URLPARAM_SETTINGSFORMAT
            // Comment Leo 09/11/2017: removed param "mangled" because it can't be used anymore on "loading-frame", since the website depends on configuration generated through /generate-theme/, which depends on the value of the template-definition
            // GD_URLPARAM_MANGLED,
        );
        $configuration['$localesByURL'] = $this->get_locales_byurl();
        $configuration['$defaultLocale'] = $this->get_default_locale();
        $configuration['$outputJSON'] = GD_URLPARAM_OUTPUT.'='.GD_URLPARAM_OUTPUT_JSON;
        $configuration['$origins'] = PoP_Frontend_ConfigurationUtils::get_allowed_domains();
        // Remove the localhost from the multidomains, or the SW won't cache anything at all
        // $homeurl = get_site_url();
        // $multidomains = array_keys(PoP_MultiDomain_Utils::get_multidomain_websites());
        // array_splice($multidomains, array_search($homeurl, $multidomains), 1);
        // $configuration['$multidomains'] = $multidomains;
        $configuration['$multidomains'] = $this->get_multidomains();
        $configuration['$cacheBustParam'] = GD_URLPARAM_SWCACHEBUST;

        // Thememodes for the appshell
        global $gd_theme_manager;
        $theme = $gd_theme_manager->get_theme();
        $configuration['$themes'] = array(
            // 'params' => array(
            //     'theme' => GD_URLPARAM_THEME,
            //     'thememode' => GD_URLPARAM_THEMEMODE,
            // ),
            'default' => $gd_theme_manager->get_default_themename(),
            'themes' => array(
                $theme->get_name() => array(
                    'default' => $theme->get_default_thememodename(),
                ),
            ),
        );

        $resourceTypes = array('static', 'json', 'html');
        $configuration['$excludedFullPaths'] = $configuration['$excludedPartialPaths'] = $configuration['$cacheItems'] = $configuration['$strategies'] = array();
        foreach ($resourceTypes as $resourceType) {

            $configuration['$excludedFullPaths'][$resourceType] = array_unique($this->get_excluded_fullpaths($resourceType));
            $configuration['$excludedPartialPaths'][$resourceType] = array_unique($this->get_excluded_partialpaths($resourceType));
            $configuration['$cacheItems'][$resourceType] = array_values(array_unique($this->get_precache_list($resourceType)));
            $configuration['$strategies'][$resourceType] = $this->get_strategies($resourceType);
            $configuration['$ignore'][$resourceType] = $this->get_ignored_params($resourceType);
        }

        // These values will be overriden in wp-content/plugins/pop-serviceworkers/plugins/pop-cdn-core/library/serviceworkers/sw-hooks.php,
        // but must declare here the empty values so that, if the plug-in is not activated, it still replaces those values in service-worker.js
        $configuration['$contentCDNOriginalDomain'] = get_site_url();
        $configuration['$contentCDNDomain'] = '';
        $configuration['$contentCDNParams'] = array();
        
        // Allow to hook the CDN configuration
        $configuration = apply_filters('PoP_ServiceWorkers_Job_SW:configuration', $configuration);

        return $configuration;
    }

    protected function get_precache_list($resourceType) {

    	$precache = array();

    	 // Add the offline and appshell pages
        if ($resourceType == 'html') {
            foreach ($this->get_appshell_pages() as $locale => $themes) {
                foreach ($themes as $theme => $thememodes) {
                    foreach ($thememodes as $thememode => $url) {

                        $precache[] = $url;
                    }
                }
            }
        }
        // elseif ($resourceType == 'json') {
        //     $precache = array_values($this->get_offline_pages());
        // }
        
        // Hook in the resources to pre-cache
        return apply_filters(
        	'PoP_ServiceWorkers_Job_CacheResources:precache',
        	$precache,
            $resourceType
        );
    }

    protected function get_excluded_fullpaths($resourceType) {
        
        // Hook in the resources to exclude
        return apply_filters(
            'PoP_ServiceWorkers_Job_Fetch:exclude:full',
            array(),
            $resourceType
        );
    }

    protected function get_excluded_partialpaths($resourceType) {
        
        // Hook in the resources to exclude
        return apply_filters(
            'PoP_ServiceWorkers_Job_Fetch:exclude:partial',
            array(),
            $resourceType
        );
    }

    protected function get_ignored_params($resourceType) {

        $ignore = array();
        if ($resourceType == 'json') {

            // Hook in the paths to include
            // All the layout loaders (eg: POP_WPAPI_PAGE_LOADERS_POSTS_LAYOUTS) belong here
            // It can be resolved to all silent_document pages without a checkpoint
            return apply_filters(
                'PoP_ServiceWorkers_Job_Fetch:ignoredparams:'.$resourceType,
                array(
                    GD_URLPARAM_SWNETWORKFIRST,
                )
            );
        }

        return $ignore;
    }

    protected function get_strategies($resourceType) {

        $strategies = array();
        if ($resourceType == 'json') {

            // $hasParams = $this->get_ignored_params($resourceType);
        
            // Hook in the paths to include
            // All the layout loaders (eg: POP_WPAPI_PAGE_LOADERS_POSTS_LAYOUTS) belong here
            // It can be resolved to all silent_document pages without a checkpoint
            $strategies['networkFirst'] = array(
                'startsWith' => array(
                    'full' => apply_filters(
                        'PoP_ServiceWorkers_Job_Fetch:strategies:'.$resourceType.':networkFirst:startsWith:full',
                        array()
                    ),
                    'partial' => apply_filters(
                        'PoP_ServiceWorkers_Job_Fetch:strategies:'.$resourceType.':networkFirst:startsWith:partial',
                        array()
                    ),
                ),
                'hasParams' => apply_filters(
                    'PoP_ServiceWorkers_Job_Fetch:strategies:'.$resourceType.':networkFirst:hasParams',
                    // $hasParams
                    array(
                        GD_URLPARAM_SWNETWORKFIRST,
                    )
                ),
            );
        }

        return $strategies;
    }

    // protected function get_offline_image() {
        
    //     // File URL: https://upload.wikimedia.org/wikipedia/commons/1/17/User-offline.svg
    //     // Attribution: By GNOME icon artists (HTTP / FTP) [CC BY-SA 3.0 (http://creativecommons.org/licenses/by-sa/3.0) or LGPL (http://www.gnu.org/copyleft/lgpl.html)], via Wikimedia Commons

    //     return '<svg role="img" aria-labelledby="offline-title" xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="display:inline;enable-background:new"><defs><linearGradient><stop style="stop-color:#ffffff;stop-opacity:1" offset="0" /><stop style="stop-color:#ffffff;stop-opacity:0" offset="1" /></linearGradient><linearGradient><stop style="stop-color:#000000;stop-opacity:1" offset="0" /><stop style="stop-color:#000000;stop-opacity:0" offset="1" /></linearGradient><linearGradient><stop style="stop-color:#f0f0ee;stop-opacity:1" offset="0" /><stop style="stop-color:#a1a196;stop-opacity:1" offset="1" /></linearGradient><radialGradient cx="319.375" cy="89.75" r="21.125" fx="319.375" fy="89.75" xlink:href="#linearGradient8930" gradientUnits="userSpaceOnUse" gradientTransform="matrix(1,0,0,0.3313609,0,60.010355)" /><radialGradient cx="319.375" cy="89.75" r="21.125" fx="319.375" fy="89.75" xlink:href="#linearGradient8930" gradientUnits="userSpaceOnUse" gradientTransform="matrix(1,0,0,0.3313609,0,60.010355)" /><radialGradient cx="21.628534" cy="7.3292775" r="5.8947372" fx="21.628534" fy="7.3292775" xlink:href="#linearGradient7134" gradientUnits="userSpaceOnUse" gradientTransform="matrix(19.74797,-5.2914524,2.5727027,9.6014571,-16.474728,94.074612)" /><linearGradient x1="433.35385" y1="53.938446" x2="449.21295" y2="101.3146" xlink:href="#linearGradient11533" gradientUnits="userSpaceOnUse" /><radialGradient cx="319.375" cy="89.75" r="21.125" fx="319.375" fy="89.75" xlink:href="#linearGradient8930" gradientUnits="userSpaceOnUse" gradientTransform="matrix(1,0,0,0.3313609,0,60.010355)" /><radialGradient cx="319.375" cy="89.75" r="21.125" fx="319.375" fy="89.75" xlink:href="#linearGradient8930" gradientUnits="userSpaceOnUse" gradientTransform="matrix(1,0,0,0.3313609,0,60.010355)" /><radialGradient cx="21.628534" cy="7.3292775" r="5.8947372" fx="21.628534" fy="7.3292775" xlink:href="#linearGradient7134" gradientUnits="userSpaceOnUse" gradientTransform="matrix(19.74797,-5.2914524,2.5727027,9.6014571,-16.474728,94.074612)" /><linearGradient x1="433.35385" y1="53.938446" x2="449.21295" y2="101.3146" xlink:href="#linearGradient11533" gradientUnits="userSpaceOnUse" /><radialGradient cx="319.375" cy="89.75" r="21.125" fx="319.375" fy="89.75" xlink:href="#linearGradient8930" gradientUnits="userSpaceOnUse" gradientTransform="matrix(1,0,0,0.3313609,0,60.010355)" /><radialGradient cx="319.375" cy="89.75" r="21.125" fx="319.375" fy="89.75" xlink:href="#linearGradient8930" gradientUnits="userSpaceOnUse" gradientTransform="matrix(1,0,0,0.3313609,0,60.010355)" /><radialGradient cx="21.628534" cy="7.3292775" r="5.8947372" fx="21.628534" fy="7.3292775" xlink:href="#linearGradient7134" gradientUnits="userSpaceOnUse" gradientTransform="matrix(19.74797,-5.2914524,2.5727027,9.6014571,-16.474728,94.074612)" /><linearGradient x1="433.35385" y1="53.938446" x2="449.21295" y2="101.3146" xlink:href="#linearGradient11533" gradientUnits="userSpaceOnUse" /><radialGradient cx="319.375" cy="89.75" r="21.125" fx="319.375" fy="89.75" xlink:href="#linearGradient8930" gradientUnits="userSpaceOnUse" gradientTransform="matrix(1,0,0,0.3313609,0,60.010355)" /><radialGradient cx="319.375" cy="89.75" r="21.125" fx="319.375" fy="89.75" xlink:href="#linearGradient8930" gradientUnits="userSpaceOnUse" gradientTransform="matrix(1,0,0,0.3313609,0,60.010355)" /><radialGradient cx="21.628534" cy="7.3292775" r="5.8947372" fx="21.628534" fy="7.3292775" xlink:href="#linearGradient7134" gradientUnits="userSpaceOnUse" gradientTransform="matrix(19.74797,-5.2914524,2.5727027,9.6014571,-16.474728,94.074612)" /><radialGradient cx="21.628534" cy="7.3292775" r="5.8947372" fx="21.628534" fy="7.3292775" xlink:href="#linearGradient7134" gradientUnits="userSpaceOnUse" gradientTransform="matrix(19.74797,-5.2914524,2.5727027,9.6014571,-16.474728,94.074612)" /><radialGradient cx="21.628534" cy="7.3292775" r="5.8947372" fx="21.628534" fy="7.3292775" xlink:href="#linearGradient7134" gradientUnits="userSpaceOnUse" gradientTransform="matrix(19.74797,-5.2914524,2.5727027,9.6014571,433.52527,94.074612)" /></defs><g transform="translate(-865.50001,-49.51429)"><path d="m 340.5,89.75 a 21.125,7 0 1 1 -42.25,0 21.125,7 0 1 1 42.25,0 z" transform="matrix(0.9576164,0,0,0.5755102,583.66127,41.347962)" style="opacity:0.35;fill:url(#radialGradient3037);fill-opacity:1;stroke:none;display:inline;enable-background:new" /><path d="m 340.5,89.75 a 21.125,7 0 1 1 -42.25,0 21.125,7 0 1 1 42.25,0 z" transform="matrix(0.8284027,0,0,0.4285713,624.9289,54.535726)" style="fill:url(#radialGradient3039);fill-opacity:1;stroke:none" /><path d="m 889.5,50.5 c -6.63601,0 -12.03124,5.80331 -12.03125,12.96875 0,3.599306 1.36878,6.866679 3.5625,9.21875 -6.24362,1.886219 -10.53125,5.909018 -10.53125,11.25 10e-6,2.703455 1.11784,7.272278 5,8.3125 10e-6,10e-7 0.0625,1.09375 0.0625,1.09375 1.25702,0.20172 2.98003,1.15625 13.9375,1.15625 0.0111,0 0.0201,4e-6 0.0312,0 10.95747,0 12.68048,-0.954534 13.9375,-1.15625 0,0 0.0625,-1.093753 0.0625,-1.09375 3.88216,-1.040222 4.99999,-5.609049 5,-8.3125 0,-5.349112 -4.3034,-9.367438 -10.5625,-11.25 2.19372,-2.352071 3.5625,-5.619444 3.5625,-9.21875 C 901.53125,56.303311 896.13601,50.5 889.5,50.5 z" style="fill:url(#radialGradient3055);fill-opacity:1;fill-rule:evenodd;stroke:#555753;stroke-width:0.99999988;stroke-linecap:butt;stroke-linejoin:round;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0;marker:none;visibility:visible;display:inline;overflow:visible;enable-background:accumulate" /><path d="m 439.5,51.65625 c -5.97091,0 -10.87499,5.219703 -10.875,11.8125 0,3.310083 1.25426,6.297703 3.25,8.4375 a 1.1409117,1.1409117 0 0 1 -0.5,1.875 c -2.97609,0.899087 -5.44403,2.29879 -7.125,4.03125 -1.68097,1.73246 -2.59375,3.759779 -2.59375,6.125 0,1.194497 0.23808,2.874396 0.90625,4.28125 0.66817,1.406854 1.65455,2.518373 3.21875,2.9375 a 1.1409117,1.1409117 0 0 1 0.84375,1.03125 l 0,0.21875 c 0.59306,0.140865 1.0244,0.282499 2.40625,0.46875 1.88623,0.254232 5.02314,0.46875 10.46875,0.46875 l 0.0312,0 c 5.44561,0 8.58252,-0.214517 10.46875,-0.46875 1.38185,-0.186251 1.81319,-0.327886 2.40625,-0.46875 0.004,-0.0696 0,-0.21875 0,-0.21875 A 1.1409117,1.1409117 0 0 1 453.25,91.15625 c 1.5642,-0.419126 2.55058,-1.530646 3.21875,-2.9375 0.66817,-1.406854 0.90625,-3.086755 0.90625,-4.28125 0,-2.368629 -0.90737,-4.392969 -2.59375,-6.125 -1.68638,-1.732031 -4.17203,-3.133681 -7.15625,-4.03125 a 1.1409117,1.1409117 0 0 1 -0.5,-1.875 c 1.99574,-2.139797 3.25,-5.127417 3.25,-8.4375 0,-6.592794 -4.90409,-11.8125 -10.875,-11.8125 z" transform="translate(450.00001,0)" style="fill:none;stroke:url(#linearGradient3000);stroke-width:0.99999988;stroke-linecap:butt;stroke-linejoin:round;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0;marker:none;visibility:visible;display:inline;overflow:visible;enable-background:accumulate" /><path d="m 886.50001,60.499998 6.09026,6.090264" style="fill:#babdb6;fill-opacity:1;stroke:#ffffff;stroke-width:1.5;stroke-linecap:square;stroke-linejoin:round;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0.3612;display:inline;enable-background:new" /><path d="m 892.59027,60.499998 -6.09026,6.090264" style="fill:#babdb6;fill-opacity:1;stroke:#ffffff;stroke-width:1.5;stroke-linecap:square;stroke-linejoin:round;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0.3612;display:inline;enable-background:new" /><path d="m 886.50001,59.499998 6.09026,6.090264" style="fill:#babdb6;fill-opacity:1;stroke:#2e3436;stroke-width:1;stroke-linecap:square;stroke-linejoin:round;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0.3612;display:inline;enable-background:new" /><path d="m 892.59027,59.499998 -6.09026,6.090264" style="fill:#babdb6;fill-opacity:1;stroke:#2e3436;stroke-width:1;stroke-linecap:square;stroke-linejoin:round;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0.3612;display:inline;enable-background:new" /></g></svg>';
    //     // return '<svg role="img" aria-labelledby="offline-title" viewBox="0 0 400 300" xmlns="http://www.w3.org/2000/svg"><title id="offline-title">Offline</title><g fill="none" fill-rule="evenodd"><path fill="#D8D8D8" d="M0 0h400v300H0z"/><text fill="#9B9B9B" font-family="Times New Roman,Times,serif" font-size="72" font-weight="bold"><tspan x="93" y="172">offline</tspan></text></g></svg>';
    // }

    // protected function get_offline_pages() {
        
    //     $pages = array();
    //     if (POP_SERVICEWORKERS_PAGE_OFFLINE) {

    //         // Add the output=json because the offline will always be called from inside the website, so by then it must all be json
    //         $url = get_permalink(POP_SERVICEWORKERS_PAGE_OFFLINE);
    //         $url = add_query_arg(GD_URLPARAM_OUTPUT, GD_URLPARAM_OUTPUT_JSON, $url);
    //         $pages[get_locale()] = $url;
    //     }

    //     // Allow qTrans to modify this
    //     return apply_filters(
    //         'PoP_ServiceWorkers_Job_Fetch:offline_pages',
    //         $pages
    //     );
    // }

    protected function get_multidomains() {

        // // $multidomain_websites = PoP_MultiDomain_Utils::get_multidomain_websites();
        // // $multidomain_locales = array();
        // // foreach ($multidomain_websites as $domain => $website_info) {
        // //     $multidomain_locales[] = $website_info['locale'];
        // // }
        // // $configuration['$multidomain'] = array(
        // //     'domains' => array_keys($multidomain_websites),
        // //     'locales' => $multidomain_locales,
        // // );
        // // Remove the localhost from the multidomains, or the SW won't cache anything at all
        
        // $multidomains = array_keys(PoP_MultiDomain_Utils::get_multidomain_websites());
        // Allow popMultiDomains to modify this
        $multidomains = array_unique(apply_filters(
            'PoP_ServiceWorkers_Job_Fetch:multidomains',
            array()
        ));

        // Make sure the homeurl is not there!
        $homeurl = get_site_url();
        $pos = array_search($homeurl, $multidomains);
        if ($pos > -1) {
            array_splice($multidomains, $pos, 1);
        }
        
        return $multidomains;
    }

    protected function get_locales() {
        
        // Allow qTrans to modify this
        return apply_filters(
            'PoP_ServiceWorkers_Job_Fetch:locales',
            array(get_locale())
        );
    }

    protected function get_appshell_url($page, $locale, $themename, $thememodename) {

        // Allow qTrans to modify this
        return apply_filters(
            'PoP_ServiceWorkers_Job_Fetch:appshell_url',
            add_query_arg(
                GD_URLPARAM_THEMEMODE, 
                $thememodename, 
                add_query_arg(
                    GD_URLPARAM_THEME, 
                    $themename, 
                    get_permalink($page)
                )
            ),
            $locale
        );
    }

    protected function get_appshell_pages() {
        
        $pages = array();
        if (POP_FRONTENDENGINE_PAGE_APPSHELL) {
            
            global $gd_theme_manager;

            // Just pre-cache the appshell for the default theme, and all of its thememodes
            $themes = array($gd_theme_manager->get_theme());
            foreach ($this->get_locales() as $locale) {

                foreach ($themes as $theme) {
                    
                    foreach ($theme->get_thememodes() as $thememode) {
                        
                        $pages[$locale][$theme->get_name()][$thememode->get_name()] = $this->get_appshell_url(POP_FRONTENDENGINE_PAGE_APPSHELL, $locale, $theme->get_name(), $thememode->get_name());
                    }
                }
            }
        }

        return apply_filters(
            'PoP_ServiceWorkers_Job_Fetch:appshell_pages',
            $pages
        );
    }

    protected function get_locales_byurl() {
        
        // Allow qTrans to modify this
        return apply_filters(
            'PoP_ServiceWorkers_Job_Fetch:locales_byurl',
            array(
                site_url() => get_locale(),
            )
        );
    }

    protected function get_default_locale() {
        
        // Allow qTrans to modify this
        return apply_filters(
            'PoP_ServiceWorkers_Job_Fetch:default_locale',
            get_locale()
        );
    }
}
    
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_ServiceWorkers_Job_SW();
