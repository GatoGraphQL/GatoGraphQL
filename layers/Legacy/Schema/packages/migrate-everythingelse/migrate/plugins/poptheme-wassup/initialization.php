<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
class PoPTheme_Wassup_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('poptheme-wassup', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Global Variables and Configuration
         */
        include_once 'config/load.php';

        /**
         * Load the Kernel
         */
        include_once 'kernel/load.php';

        /**
         * Load the Library
         */
        include_once 'library/load.php';

        /**
         * Load the plugins' libraries
         */
        // Execute after everything else, so that the other plugins have loaded
        \PoP\Root\App::getHookManager()->addAction(
            'plugins_loaded',
            function () {
                include_once 'plugins/load.php';
            },
            8881500
        );

        /**
         * Load the Themes
         */
        include_once 'themes/load.php';

        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        if (!$cmsapplicationapi->isAdminPanel()) {
            if (defined('POP_ENGINEWEBPLATFORM_INITIALIZED')) {
                \PoP\Root\App::getHookManager()->addAction('popcms:enqueueScripts', array($this, 'optimizeScripts'), 0);

                // Priority 0: print "style.css" immediately, so it starts rendering and applying these styles before anything else
                \PoP\Root\App::getHookManager()->addAction('popcms:printStyles', array($this, 'registerStyles'), 0);
            }
        }
    }

    public function optimizeScripts()
    {

        // Move scripts to the footer (they are not needed immediately anyway!)
        // Wanted to do it also for jQuery, but it breaks everything!
        // Taken from http://www.joanmiquelviade.com/how-to-move-jquery-script-to-the-footer/
        global $wp_scripts;
        ;
        // $wp_scripts->registered['utils']->extra['group'] = 1;
        // $wp_scripts->registered['plupload']->extra['group'] = 1;
        $wp_scripts->add_data('utils', 'group', 1);
        $wp_scripts->add_data('plupload', 'group', 1);
    }

    public function registerStyles()
    {

        // Only if not doing code splitting then load the resources. Otherwise, the resources will be loaded by the ResourceLoader
        if (PoP_WebPlatform_ConfigurationUtils::registerScriptsAndStylesDuringInit()) {
            $htmlcssplatformapi = \PoP\EngineHTMLCSSPlatform\FunctionAPIFactory::getInstance();
            $css_folder = POPTHEME_WASSUP_URL.'/css';
            $dist_css_folder = $css_folder . '/dist';
            $includes_css_folder = $css_folder . '/includes';
            $cdn_css_folder = $includes_css_folder . '/cdn';

            /* ------------------------------
            * 3rd Party Libraries (using CDN whenever possible)
            ----------------------------- */

            if (PoP_WebPlatform_ServerUtils::accessExternalcdnResources()) {
                // CDN
                $htmlcssplatformapi->registerStyle('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css', null, null);
            } else {
                // Locally stored files
                $htmlcssplatformapi->registerStyle('font-awesome', $cdn_css_folder . '/font-awesome.4.7.0.min.css', null, null);
            }
            $htmlcssplatformapi->enqueueStyle('font-awesome');
        }
    }
}
