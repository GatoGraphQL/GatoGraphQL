<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
class PoP_PrettyPrint_Initialization
{
    public function initialize()
    {

        // load_plugin_textdomain('pop-prettyprint', false, dirname(plugin_basename(__FILE__)).'/languages');

        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        if (!$cmsapplicationapi->isAdminPanel()) {
            HooksAPIFacade::getInstance()->addAction("popcms:enqueueScripts", array($this, 'registerScripts'));
            HooksAPIFacade::getInstance()->addAction('popcms:printStyles', array($this, 'registerStyles'), 100);
        }

        /**
         * Load the PoP Library
         */
        include_once 'library/load.php';

        /**
         * Load the Plugins Library
         */
        include_once 'plugins/load.php';
    }

    public function registerScripts()
    {

        // Only if not doing code splitting then load the resources. Otherwise, the resources will be loaded by the ResourceLoader
        if (PoP_WebPlatform_ConfigurationUtils::registerScriptsAndStylesDuringInit()) {
            $cmswebplatformapi = \PoP\EngineWebPlatform\FunctionAPIFactory::getInstance();
            $js_folder = POP_PRETTYPRINT_URL.'/js';
            $includes_js_folder = $js_folder.'/includes';
            $cdn_js_folder = $includes_js_folder . '/cdn';
            $dist_js_folder = $js_folder.'/dist';
            $libraries_js_folder = (PoP_WebPlatform_ServerUtils::useMinifiedResources() ? $dist_js_folder : $js_folder).'/libraries';
            $suffix = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? '.min' : '';
            $bundles_js_folder = $dist_js_folder.'/bundles';

            if (PoP_WebPlatform_ServerUtils::accessExternalcdnResources()) {
                // CDN
                // https://github.com/google/code-prettify
                $cmswebplatformapi->registerScript('code-prettify', 'https://cdn.rawgit.com/google/code-prettify/master/loader/prettify.js', null, null);
            } else {
                // Local files
                $cmswebplatformapi->registerScript('code-prettify', $cdn_js_folder . '/google-code-prettify/prettify.js', null, null);
            }
            $cmswebplatformapi->enqueueScript('code-prettify');

            if (PoP_WebPlatform_ServerUtils::useBundledResources()) {
                $cmswebplatformapi->registerScript('pop-prettyprint', $bundles_js_folder . '/pop-prettyprint.bundle.min.js', array('pop', 'jquery'), POP_PRETTYPRINT_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-prettyprint');
            } else {
                $cmswebplatformapi->registerScript('pop-prettyprint', $libraries_js_folder.'/pop-prettyprint'.$suffix.'.js', array('jquery', 'pop'), POP_PRETTYPRINT_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-prettyprint');
            }
        }
    }

    public function registerStyles()
    {

        // Only if not doing code splitting then load the resources. Otherwise, the resources will be loaded by the ResourceLoader
        if (PoP_WebPlatform_ConfigurationUtils::registerScriptsAndStylesDuringInit()) {
            $htmlcssplatformapi = \PoP\EngineHTMLCSSPlatform\FunctionAPIFactory::getInstance();
            if (PoP_WebPlatform_ServerUtils::accessExternalcdnResources()) {
                $htmlcssplatformapi->registerStyle('code-prettify', 'https://cdn.rawgit.com/google/code-prettify/master/styles/desert.css', null, null);
            } else {
                $css_folder = POP_PRETTYPRINT_URL.'/css';
                $includes_css_folder = $css_folder . '/includes';
                $cdn_css_folder = $includes_css_folder . '/cdn';

                $htmlcssplatformapi->registerStyle('code-prettify', $cdn_css_folder . '/google-code-prettify/desert.css', null, null);
            }
            $htmlcssplatformapi->enqueueStyle('code-prettify');
        }
    }
}
