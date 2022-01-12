<?php
use PoP\ComponentModel\Facades\Info\ApplicationInfoFacade;
use PoP\ComponentModel\State\ApplicationState;
use PoP\FileStore\Facades\FileRendererFacade;
use PoP\Hooks\Facades\HooksAPIFacade;

class PoP_ThemeWassupWebPlatform_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('poptheme-wassup-webplatform', false, dirname(plugin_basename(__FILE__)).'/languages');

        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        if (!$cmsapplicationapi->isAdminPanel()) {
            // After PoP
            HooksAPIFacade::getInstance()->addAction('popcms:enqueueScripts', array($this, 'registerScripts'), 100);

            // Priority 0: print "style.css" immediately, so it starts rendering and applying these styles before anything else
            HooksAPIFacade::getInstance()->addAction('popcms:printStyles', array($this, 'registerStyles'), 0);

            // Inline styles
            HooksAPIFacade::getInstance()->addAction('popcms:head', array($this, 'printInlineStyles'));
        }

        /**
         * Global Variables and Configuration
         */
        include_once 'config/load.php';

        /**
         * Load the PoP Library
         */
        include_once 'library/load.php';

        /**
         * Load the Plugins libraries
         */
        include_once 'plugins/load.php';
    }

    public function registerScripts()
    {

        // Only if not doing code splitting then load the resources. Otherwise, the resources will be loaded by the ResourceLoader
        if (PoP_WebPlatform_ConfigurationUtils::registerScriptsAndStylesDuringInit()) {
            $cmswebplatformapi = \PoP\EngineWebPlatform\FunctionAPIFactory::getInstance();
            $js_folder = POPTHEME_WASSUPWEBPLATFORM_URL.'/js';
            $dist_js_folder = $js_folder.'/dist';
            $libraries_js_folder = (PoP_WebPlatform_ServerUtils::useMinifiedResources() ? $dist_js_folder : $js_folder).'/libraries';
            $suffix = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? '.min' : '';
            $bundles_js_folder = $dist_js_folder.'/bundles';

            // Load different files depending on the environment (PROD / DEV)
            if (PoP_WebPlatform_ServerUtils::useBundledResources()) {
                // All MESYM Theme Customization Templates
                $cmswebplatformapi->registerScript('poptheme-wassup-templates', $bundles_js_folder . '/poptheme-wassup.templates.bundle.min.js', array(), POPTHEME_WASSUPWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('poptheme-wassup-templates');

                // Custom Theme JS Minified
                $cmswebplatformapi->registerScript('poptheme-wassup', $bundles_js_folder . '/poptheme-wassup.bundle.min.js', array('jquery', 'pop'), POPTHEME_WASSUPWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('poptheme-wassup');
            } else {
                $cmswebplatformapi->registerScript('poptheme-wassup-pagesection-manager', $libraries_js_folder . '/custom-pagesection-manager'.$suffix.'.js', array('jquery', 'pop'), POPTHEME_WASSUPWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('poptheme-wassup-pagesection-manager');

                $cmswebplatformapi->registerScript('poptheme-wassup-conditions', $libraries_js_folder . '/condition-functions'.$suffix.'.js', array('jquery', 'pop'), POPTHEME_WASSUPWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('poptheme-wassup-conditions');

                $cmswebplatformapi->registerScript('poptheme-wassup-bootstrapcustomfunctions', $libraries_js_folder . '/bootstrap/bootstrap-custom-functions'.$suffix.'.js', array('jquery', 'pop'), POPTHEME_WASSUPWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('poptheme-wassup-bootstrapcustomfunctions');

                $cmswebplatformapi->registerScript('poptheme-wassup', $libraries_js_folder . '/custom-functions'.$suffix.'.js', array('jquery', 'pop'), POPTHEME_WASSUPWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('poptheme-wassup');

                // Templates
                $this->enqueueTemplatesScripts();
            }
        }
    }

    public function enqueueTemplatesScripts()
    {
        $cmswebplatformapi = \PoP\EngineWebPlatform\FunctionAPIFactory::getInstance();
        $folder = POPTHEME_WASSUPWEBPLATFORM_URL.'/js/dist/templates/';

        // All Custom Templates
        $cmswebplatformapi->enqueueScript('frame-top-tmpl', $folder.'frame-top.tmpl.js', array('handlebars'), POPTHEME_WASSUPWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('frame-side-tmpl', $folder.'frame-side.tmpl.js', array('handlebars'), POPTHEME_WASSUPWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('frame-background-tmpl', $folder.'frame-background.tmpl.js', array('handlebars'), POPTHEME_WASSUPWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('frame-topsimple-tmpl', $folder.'frame-topsimple.tmpl.js', array('handlebars'), POPTHEME_WASSUPWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('pagesectionextension-frameoptions-tmpl', $folder.'pagesectionextension-frameoptions.tmpl.js', array('handlebars'), POPTHEME_WASSUPWEBPLATFORM_VERSION, true);
    }

    public function registerStyles()
    {

        // Only if not doing code splitting then load the resources. Otherwise, the resources will be loaded by the ResourceLoader
        if (PoP_WebPlatform_ConfigurationUtils::registerScriptsAndStylesDuringInit()) {
            $htmlcssplatformapi = \PoP\EngineHTMLCSSPlatform\FunctionAPIFactory::getInstance();
            $css_folder = POPTHEME_WASSUPWEBPLATFORM_URL.'/css';
            $dist_css_folder = $css_folder . '/dist';
            $includes_css_folder = $css_folder . '/includes';
            $cdn_css_folder = $includes_css_folder . '/cdn';
            $libraries_css_folder = (PoP_WebPlatform_ServerUtils::useMinifiedResources() ? $dist_css_folder : $css_folder).'/libraries';
            $templates_css_folder = (PoP_WebPlatform_ServerUtils::useMinifiedResources() ? $dist_css_folder : $css_folder).'/templates';
            $suffix = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? '.min' : '';
            $bundles_css_folder = $dist_css_folder . '/bundles';

            /* ------------------------------
            * Local Libraries (enqueue first)
            ----------------------------- */

            if (PoP_WebPlatform_ServerUtils::useBundledResources()) {
                $htmlcssplatformapi->registerStyle('poptheme-wassup', $bundles_css_folder . '/poptheme-wassup.bundle.min.css', array('bootstrap'), POPTHEME_WASSUPWEBPLATFORM_VERSION);
                $htmlcssplatformapi->enqueueStyle('poptheme-wassup');
            } else {
                $htmlcssplatformapi->registerStyle('poptheme-wassup-pagesectiongroup', $libraries_css_folder . '/pagesection-group'.$suffix.'.css', array(), POPTHEME_WASSUPWEBPLATFORM_VERSION, 'screen');
                $htmlcssplatformapi->enqueueStyle('poptheme-wassup-pagesectiongroup');

                // Theme CSS Source
                $htmlcssplatformapi->registerStyle('poptheme-wassup', $libraries_css_folder.'/style'.$suffix.'.css', array('bootstrap'), POPTHEME_WASSUPWEBPLATFORM_VERSION);
                $htmlcssplatformapi->enqueueStyle('poptheme-wassup');

                // Custom Theme Source
                // Custom implementation of Bootstrap
                $htmlcssplatformapi->registerStyle('poptheme-wassup-bootstrap', $libraries_css_folder . '/custom.bootstrap'.$suffix.'.css', array('bootstrap'), POPTHEME_WASSUPWEBPLATFORM_VERSION, 'screen');
                $htmlcssplatformapi->enqueueStyle('poptheme-wassup-bootstrap');

                $htmlcssplatformapi->registerStyle('poptheme-wassup-typeahead-bootstrap', $libraries_css_folder . '/typeahead.js-bootstrap'.$suffix.'.css', array('bootstrap'), POPTHEME_WASSUPWEBPLATFORM_VERSION, 'screen');
                $htmlcssplatformapi->enqueueStyle('poptheme-wassup-typeahead-bootstrap');

                $htmlcssplatformapi->registerStyle('poptheme-wassup-blockgroup-home-welcome', $templates_css_folder . '/blockgroup-home-welcome'.$suffix.'.css', array(), POPTHEME_WASSUPWEBPLATFORM_VERSION, 'screen');
                $htmlcssplatformapi->enqueueStyle('poptheme-wassup-blockgroup-home-welcome');

                $htmlcssplatformapi->registerStyle('poptheme-wassup-collapse-hometop', $templates_css_folder . '/collapse-hometop'.$suffix.'.css', array(), POPTHEME_WASSUPWEBPLATFORM_VERSION, 'screen');
                $htmlcssplatformapi->enqueueStyle('poptheme-wassup-collapse-hometop');

                $htmlcssplatformapi->registerStyle('poptheme-wassup-quicklinkgroups', $templates_css_folder . '/quicklinkgroups'.$suffix.'.css', array(), POPTHEME_WASSUPWEBPLATFORM_VERSION, 'screen');
                $htmlcssplatformapi->enqueueStyle('poptheme-wassup-quicklinkgroups');

                $htmlcssplatformapi->registerStyle('poptheme-wassup-daterangepicker', $templates_css_folder . '/daterangepicker'.$suffix.'.css', array(), POPTHEME_WASSUPWEBPLATFORM_VERSION, 'screen');
                $htmlcssplatformapi->enqueueStyle('poptheme-wassup-daterangepicker');

                $htmlcssplatformapi->registerStyle('poptheme-wassup-skeletonscreen', $templates_css_folder . '/skeleton-screen'.$suffix.'.css', array(), POPTHEME_WASSUPWEBPLATFORM_VERSION, 'screen');
                $htmlcssplatformapi->enqueueStyle('poptheme-wassup-skeletonscreen');

                $htmlcssplatformapi->registerStyle('poptheme-wassup-blockcarousel', $templates_css_folder . '/block-carousel'.$suffix.'.css', array(), POPTHEME_WASSUPWEBPLATFORM_VERSION, 'screen');
                $htmlcssplatformapi->enqueueStyle('poptheme-wassup-blockcarousel');

                $htmlcssplatformapi->registerStyle('poptheme-wassup-fetchmore', $templates_css_folder . '/fetchmore'.$suffix.'.css', array(), POPTHEME_WASSUPWEBPLATFORM_VERSION, 'screen');
                $htmlcssplatformapi->enqueueStyle('poptheme-wassup-fetchmore');

                $htmlcssplatformapi->registerStyle('poptheme-wassup-blockgroup-author', $templates_css_folder . '/blockgroup-author'.$suffix.'.css', array(), POPTHEME_WASSUPWEBPLATFORM_VERSION, 'screen');
                $htmlcssplatformapi->enqueueStyle('poptheme-wassup-blockgroup-author');

                $htmlcssplatformapi->registerStyle('poptheme-wassup-blockgroup-authorsections', $templates_css_folder . '/blockgroup-authorsections'.$suffix.'.css', array(), POPTHEME_WASSUPWEBPLATFORM_VERSION, 'screen');
                $htmlcssplatformapi->enqueueStyle('poptheme-wassup-blockgroup-authorsections');

                $htmlcssplatformapi->registerStyle('poptheme-wassup-block', $templates_css_folder . '/block'.$suffix.'.css', array(), POPTHEME_WASSUPWEBPLATFORM_VERSION, 'screen');
                $htmlcssplatformapi->enqueueStyle('poptheme-wassup-block');

                $htmlcssplatformapi->registerStyle('poptheme-wassup-functionalblock', $templates_css_folder . '/functionalblock'.$suffix.'.css', array(), POPTHEME_WASSUPWEBPLATFORM_VERSION, 'screen');
                $htmlcssplatformapi->enqueueStyle('poptheme-wassup-functionalblock');

                $htmlcssplatformapi->registerStyle('poptheme-wassup-functionbutton', $templates_css_folder . '/functionbutton'.$suffix.'.css', array(), POPTHEME_WASSUPWEBPLATFORM_VERSION, 'screen');
                $htmlcssplatformapi->enqueueStyle('poptheme-wassup-functionbutton');

                $htmlcssplatformapi->registerStyle('poptheme-wassup-socialmedia', $templates_css_folder . '/socialmedia'.$suffix.'.css', array(), POPTHEME_WASSUPWEBPLATFORM_VERSION, 'screen');
                $htmlcssplatformapi->enqueueStyle('poptheme-wassup-socialmedia');

                $htmlcssplatformapi->registerStyle('poptheme-wassup-form-mypreferences', $templates_css_folder . '/form-mypreferences'.$suffix.'.css', array(), POPTHEME_WASSUPWEBPLATFORM_VERSION, 'screen');
                $htmlcssplatformapi->enqueueStyle('poptheme-wassup-form-mypreferences');

                $htmlcssplatformapi->registerStyle('poptheme-wassup-block-comments', $templates_css_folder . '/block-comments'.$suffix.'.css', array(), POPTHEME_WASSUPWEBPLATFORM_VERSION, 'screen');
                $htmlcssplatformapi->enqueueStyle('poptheme-wassup-block-comments');

                $htmlcssplatformapi->registerStyle('poptheme-wassup-frame-addcomments', $templates_css_folder . '/frame-addcomments'.$suffix.'.css', array(), POPTHEME_WASSUPWEBPLATFORM_VERSION, 'screen');
                $htmlcssplatformapi->enqueueStyle('poptheme-wassup-frame-addcomments');

                $htmlcssplatformapi->registerStyle('poptheme-wassup-side-sections-menu', $templates_css_folder . '/side-sections-menu'.$suffix.'.css', array(), POPTHEME_WASSUPWEBPLATFORM_VERSION, 'screen');
                $htmlcssplatformapi->enqueueStyle('poptheme-wassup-side-sections-menu');

                $htmlcssplatformapi->registerStyle('poptheme-wassup-littleguy', $templates_css_folder . '/littleguy'.$suffix.'.css', array(), POPTHEME_WASSUPWEBPLATFORM_VERSION, 'screen');
                $htmlcssplatformapi->enqueueStyle('poptheme-wassup-littleguy');

                $htmlcssplatformapi->registerStyle('poptheme-wassup-speechbubble', $templates_css_folder . '/speechbubble'.$suffix.'.css', array(), POPTHEME_WASSUPWEBPLATFORM_VERSION, 'screen');
                $htmlcssplatformapi->enqueueStyle('poptheme-wassup-speechbubble');

                $htmlcssplatformapi->registerStyle('poptheme-wassup-featuredimage', $templates_css_folder . '/featuredimage'.$suffix.'.css', array(), POPTHEME_WASSUPWEBPLATFORM_VERSION, 'screen');
                $htmlcssplatformapi->enqueueStyle('poptheme-wassup-featuredimage');

                $htmlcssplatformapi->registerStyle('poptheme-wassup-multiselect', $templates_css_folder . '/multiselect'.$suffix.'.css', array(), POPTHEME_WASSUPWEBPLATFORM_VERSION, 'screen');
                $htmlcssplatformapi->enqueueStyle('poptheme-wassup-multiselect');

                $htmlcssplatformapi->registerStyle('poptheme-wassup-homemessage', $templates_css_folder . '/homemessage'.$suffix.'.css', array(), POPTHEME_WASSUPWEBPLATFORM_VERSION, 'screen');
                $htmlcssplatformapi->enqueueStyle('poptheme-wassup-homemessage');

                $htmlcssplatformapi->registerStyle('poptheme-wassup-smalldetails', $templates_css_folder . '/smalldetails'.$suffix.'.css', array(), POPTHEME_WASSUPWEBPLATFORM_VERSION, 'screen');
                $htmlcssplatformapi->enqueueStyle('poptheme-wassup-smalldetails');

                $htmlcssplatformapi->registerStyle('poptheme-wassup-block-notifications', $templates_css_folder . '/block-notifications'.$suffix.'.css', array(), POPTHEME_WASSUPWEBPLATFORM_VERSION, 'screen');
                $htmlcssplatformapi->enqueueStyle('poptheme-wassup-block-notifications');

                $htmlcssplatformapi->registerStyle('poptheme-wassup-scroll-notifications', $templates_css_folder . '/scroll-notifications'.$suffix.'.css', array(), POPTHEME_WASSUPWEBPLATFORM_VERSION, 'screen');
                $htmlcssplatformapi->enqueueStyle('poptheme-wassup-scroll-notifications');

                $htmlcssplatformapi->registerStyle('poptheme-wassup-widget', $templates_css_folder . '/widget'.$suffix.'.css', array(), POPTHEME_WASSUPWEBPLATFORM_VERSION, 'screen');
                $htmlcssplatformapi->enqueueStyle('poptheme-wassup-widget');

                $htmlcssplatformapi->registerStyle('poptheme-wassup-dynamicmaxheight', $templates_css_folder . '/dynamicmaxheight'.$suffix.'.css', array(), POPTHEME_WASSUPWEBPLATFORM_VERSION, 'screen');
                $htmlcssplatformapi->enqueueStyle('poptheme-wassup-dynamicmaxheight');

                $htmlcssplatformapi->registerStyle('poptheme-wassup-structure', $templates_css_folder . '/structure'.$suffix.'.css', array(), POPTHEME_WASSUPWEBPLATFORM_VERSION, 'screen');
                $htmlcssplatformapi->enqueueStyle('poptheme-wassup-structure');

                $htmlcssplatformapi->registerStyle('poptheme-wassup-layout', $templates_css_folder . '/layout'.$suffix.'.css', array(), POPTHEME_WASSUPWEBPLATFORM_VERSION, 'screen');
                $htmlcssplatformapi->enqueueStyle('poptheme-wassup-layout');

                $htmlcssplatformapi->registerStyle('poptheme-wassup-sectionlayout', $templates_css_folder.'/section-layout'.$suffix.'.css', array('bootstrap'), POPTHEME_WASSUPWEBPLATFORM_VERSION);
                $htmlcssplatformapi->enqueueStyle('poptheme-wassup-sectionlayout');
            }

            // This file is generated dynamically, so it can't be added to any bundle or minified
            // That's why we use popVersion() as its version, so upgrading the website will fetch again this file
            global $popthemewassup_backgroundimage_file, $popthemewassup_feedthumb_file;
            if (PoP_WebPlatform_ServerUtils::loadDynamicallyGeneratedResourceFiles()) {
                $htmlcssplatformapi->registerStyle('poptheme-wassup-backgroundimage', $popthemewassup_backgroundimage_file->getFileurl(), array(), ApplicationInfoFacade::getInstance()->getVersion());
                $htmlcssplatformapi->enqueueStyle('poptheme-wassup-backgroundimage');
            }
            if (PoP_WebPlatform_ServerUtils::loadDynamicallyGeneratedResourceFiles()) {
                $htmlcssplatformapi->registerStyle('poptheme-wassup-feedthumb', $popthemewassup_feedthumb_file->getFileurl(), array(), ApplicationInfoFacade::getInstance()->getVersion());
                $htmlcssplatformapi->enqueueStyle('poptheme-wassup-feedthumb');
            }
        }
    }

    public function printInlineStyles($styles)
    {

        // Only if not doing code splitting then load the resources. Otherwise, the resources will be loaded by the ResourceLoader
        if (PoP_WebPlatform_ConfigurationUtils::registerScriptsAndStylesDuringInit()) {
            if (!PoP_WebPlatform_ServerUtils::loadDynamicallyGeneratedResourceFiles()) {
                global $popthemewassup_backgroundimage_file, $popthemewassup_feedthumb_file;
                $styles = FileRendererFacade::getInstance()->render($popthemewassup_backgroundimage_file);
                $styles .= FileRendererFacade::getInstance()->render($popthemewassup_feedthumb_file);
                printf('<style type="text/css">%s</style>', $styles);
            }
        }

        return $styles;
    }
}
