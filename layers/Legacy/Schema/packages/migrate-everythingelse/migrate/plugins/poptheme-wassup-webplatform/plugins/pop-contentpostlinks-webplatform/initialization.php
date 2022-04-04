<?php
class PopThemeWassup_ContentPostLinks_Initialization
{
    public function __construct()
    {
        $this->initialize();
    }

    public function initialize()
    {
        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        if (!$cmsapplicationapi->isAdminPanel()) {
            \PoP\Root\App::addAction('popcms:printStyles', $this->registerStyles(...), 50);
        }
    }

    public function registerStyles()
    {

        // Only if not doing code splitting then load the resources. Otherwise, the resources will be loaded by the ResourceLoader
        if (PoP_WebPlatform_ConfigurationUtils::registerScriptsAndStylesDuringInit()) {
            $htmlcssplatformapi = \PoP\EngineHTMLCSSPlatform\FunctionAPIFactory::getInstance();
            if (!PoP_WebPlatform_ServerUtils::useBundledResources()) {
                $css_folder = POPTHEME_WASSUPWEBPLATFORM_URL.'/css';
                $dist_css_folder = $css_folder . '/dist';
                $libraries_css_folder = (PoP_WebPlatform_ServerUtils::useMinifiedResources() ? $dist_css_folder : $css_folder).'/libraries';
                $suffix = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? '.min' : '';

                $htmlcssplatformapi->registerStyle('poptheme-wassup-cpl-styles', $libraries_css_folder . '/plugins/pop-contentpostlinks/styles'.$suffix.'.css', array(), POPTHEME_WASSUPWEBPLATFORM_VERSION, 'screen');
                $htmlcssplatformapi->enqueueStyle('poptheme-wassup-cpl-styles');
            }
        }
    }
}

/**
 * Initialization
 */
new PopThemeWassup_ContentPostLinks_Initialization();
