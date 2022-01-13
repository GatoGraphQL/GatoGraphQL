<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
class PoPTheme_Wassup_EM_Initialization
{
    public function __construct()
    {
        $this->initialize();
    }

    public function initialize()
    {
        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        if (!$cmsapplicationapi->isAdminPanel()) {
            \PoP\Root\App::getHookManager()->addAction('popcms:printStyles', array($this, 'registerStyles'), 50);
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
                $templates_css_folder = (PoP_WebPlatform_ServerUtils::useMinifiedResources() ? $dist_css_folder : $css_folder).'/templates';
                $suffix = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? '.min' : '';

                $htmlcssplatformapi->registerStyle('poptheme-wassup-em-map', $templates_css_folder . '/plugins/pop-locations/map'.$suffix.'.css', array(), POPTHEME_WASSUPWEBPLATFORM_VERSION, 'screen');
                $htmlcssplatformapi->enqueueStyle('poptheme-wassup-em-map');
            }
        }
    }
}

/**
 * Initialization
 */
new PoPTheme_Wassup_EM_Initialization();
