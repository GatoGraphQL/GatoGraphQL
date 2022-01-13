<?php
class PoP_CategoryPostsWebPlatform_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-categoryposts-webplatform', false, dirname(plugin_basename(__FILE__)).'/languages');

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
            // After PoPTheme MESYM
            \PoP\Root\App::addAction('popcms:printStyles', array($this, 'registerStyles'), 110);
        }
    }

    public function registerStyles()
    {

        // Only if not doing code splitting then load the resources. Otherwise, the resources will be loaded by the ResourceLoader
        if (PoP_WebPlatform_ConfigurationUtils::registerScriptsAndStylesDuringInit()) {
            $htmlcssplatformapi = \PoP\EngineHTMLCSSPlatform\FunctionAPIFactory::getInstance();
            $css_folder = POP_CATEGORYPOSTSWEBPLATFORM_URL.'/css';
            $dist_css_folder = $css_folder . '/dist';
            
            if (PoP_WebPlatform_ServerUtils::useBundledResources()) {
                $bundles_css_folder = $dist_css_folder . '/bundles';
                $htmlcssplatformapi->registerStyle('pop-categoryposts-webplatform', $bundles_css_folder . '/pop-categoryposts.bundle.min.css', array('bootstrap'), POP_CATEGORYPOSTSWEBPLATFORM_VERSION);
                $htmlcssplatformapi->enqueueStyle('pop-categoryposts-webplatform');
            } else {
                $libraries_css_folder = (PoP_WebPlatform_ServerUtils::useMinifiedResources() ? $dist_css_folder : $css_folder).'/libraries';
                $suffix = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? '.min' : '';

                $htmlcssplatformapi->registerStyle('pop-categoryposts-webplatform-layout', $libraries_css_folder.'/category-layout'.$suffix.'.css', array('bootstrap'), POP_CATEGORYPOSTSWEBPLATFORM_VERSION);
                $htmlcssplatformapi->enqueueStyle('pop-categoryposts-webplatform-layout');
            }
        }
    }
}
