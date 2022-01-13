<?php
class PhotoSwipe_PoP_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('photoswipe-pop', false, dirname(plugin_basename(__FILE__)).'/languages');
        
        /**
         * Load the PoP Library
         */
        include_once 'library/load.php';
        
        /**
         * Load the Plugins Library
         */
        include_once 'plugins/load.php';

        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        if (!$cmsapplicationapi->isAdminPanel()) {
            \PoP\Root\App::addAction('popcms:printStyles', array($this, 'registerStyles'));
            \PoP\Root\App::addAction('popcms:enqueueScripts', array($this, 'registerScripts'));
        }

        \PoP\Root\App::addAction(
            'popcms:footer', 
            array($this, 'printFooterModule')
        );
    }

    public function printFooterModule()
    {

        // Include the Footer template
        ob_start();
        include PHOTOSWIPEPOP_DIR.'/templates/footer.php';
        print(ob_get_clean());
    }

    public function registerScripts()
    {

        // Only if not doing code splitting then load the resources. Otherwise, the resources will be loaded by the ResourceLoader
        if (PoP_WebPlatform_ConfigurationUtils::registerScriptsAndStylesDuringInit()) {
            $cmswebplatformapi = \PoP\EngineWebPlatform\FunctionAPIFactory::getInstance();
            $js_folder = PHOTOSWIPEPOP_URL.'/js';
            $dist_js_folder = $js_folder.'/dist';
            $libraries_js_folder = (PoP_WebPlatform_ServerUtils::useMinifiedResources() ? $dist_js_folder : $js_folder).'/libraries';
            $suffix = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? '.min' : '';
            $bundles_js_folder = $dist_js_folder.'/bundles';

            // Load different files depending on the environment (PROD / DEV)
            if (PoP_WebPlatform_ServerUtils::accessExternalcdnResources()) {
                // https://github.com/dimsemenov/PhotoSwipe/releases
                $cmswebplatformapi->registerScript('photoswipe', 'https://cdnjs.cloudflare.com/ajax/libs/photoswipe/'.PHOTOSWIPEPOP_PHOTOSWIPE_VERSION.'/photoswipe.min.js', null, null);
                $cmswebplatformapi->registerScript('photoswipe-skin', 'https://cdnjs.cloudflare.com/ajax/libs/photoswipe/'.PHOTOSWIPEPOP_PHOTOSWIPE_VERSION.'/photoswipe-ui-default.min.js', null, null);
            } else {
                $includes_uri = $js_folder.'/includes/cdn/'.PHOTOSWIPEPOP_PHOTOSWIPE_VERSION;
                $cmswebplatformapi->registerScript('photoswipe', $includes_uri.'/photoswipe.min.js', null, null);
                $cmswebplatformapi->registerScript('photoswipe-skin', $includes_uri.'/photoswipe-ui-default.min.js', null, null);
            }
            $cmswebplatformapi->enqueueScript('photoswipe');
            $cmswebplatformapi->enqueueScript('photoswipe-skin');
        
            if (PoP_WebPlatform_ServerUtils::useBundledResources()) {
                $cmswebplatformapi->registerScript('photoswipe-pop', $bundles_js_folder.'/photoswipe-pop.bundle.min.js', array('jquery', 'pop', 'photoswipe'), PHOTOSWIPEPOP_VERSION, true);
            } else {
                $cmswebplatformapi->registerScript('photoswipe-pop', $libraries_js_folder.'/photoswipe-pop'.$suffix.'.js', array('jquery', 'pop', 'photoswipe'), PHOTOSWIPEPOP_VERSION, true);
            }
            $cmswebplatformapi->enqueueScript('photoswipe-pop');
        }
    }

    public function registerStyles()
    {

        // Only if not doing code splitting then load the resources. Otherwise, the resources will be loaded by the ResourceLoader
        if (PoP_WebPlatform_ConfigurationUtils::registerScriptsAndStylesDuringInit()) {
            $htmlcssplatformapi = \PoP\EngineHTMLCSSPlatform\FunctionAPIFactory::getInstance();
            // Load different files depending on the environment (PROD / DEV)
            if (PoP_WebPlatform_ServerUtils::accessExternalcdnResources()) {
                $htmlcssplatformapi->registerStyle('photoswipe', 'https://cdnjs.cloudflare.com/ajax/libs/photoswipe/'.PHOTOSWIPEPOP_PHOTOSWIPE_VERSION.'/photoswipe.min.css', null, null);
                $htmlcssplatformapi->registerStyle('photoswipe-skin', 'https://cdnjs.cloudflare.com/ajax/libs/photoswipe/'.PHOTOSWIPEPOP_PHOTOSWIPE_VERSION.'/default-skin/default-skin.min.css', null, null);
            } else {
                $css_uri = PHOTOSWIPEPOP_URL.'/css/includes/cdn/'.PHOTOSWIPEPOP_PHOTOSWIPE_VERSION;
                $htmlcssplatformapi->registerStyle('photoswipe', $css_uri.'/photoswipe.min.css', null, null);
                $htmlcssplatformapi->registerStyle('photoswipe-skin', $css_uri.'/default-skin/default-skin.min.css', null, null);
            }
            $htmlcssplatformapi->enqueueStyle('photoswipe');
            $htmlcssplatformapi->enqueueStyle('photoswipe-skin');
        }
    }
}
