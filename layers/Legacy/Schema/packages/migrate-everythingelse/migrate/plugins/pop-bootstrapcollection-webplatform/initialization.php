<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
class PoP_BootstrapCollectionWebPlatform_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-bootstrapcollection-webplatform', false, dirname(plugin_basename(__FILE__)).'/languages');

        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        if (!$cmsapplicationapi->isAdminPanel()) {
            HooksAPIFacade::getInstance()->addAction('popcms:enqueueScripts', array($this, 'registerScripts'));
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
            $js_folder = POP_BOOTSTRAPCOLLECTIONWEBPLATFORM_URL.'/js';
            $dist_js_folder = $js_folder.'/dist';
            $libraries_js_folder = (PoP_WebPlatform_ServerUtils::useMinifiedResources() ? $dist_js_folder : $js_folder).'/libraries';
            $suffix = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? '.min' : '';
            $bundles_js_folder = $dist_js_folder.'/bundles';
            $includes_js_folder = $js_folder.'/includes';
            $cdn_js_folder = $includes_js_folder . '/cdn';

            if (PoP_WebPlatform_ServerUtils::accessExternalcdnResources()) {
                $cmswebplatformapi->registerScript('bootstrap-multiselect', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.min.js', array('bootstrap'), null);
                
                // https://github.com/dangrossman/bootstrap-daterangepicker/releases
                $cmswebplatformapi->registerScript('daterangepicker', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/2.1.24/daterangepicker.min.js', array('bootstrap'), null);
            } else {
                $cmswebplatformapi->registerScript('bootstrap-multiselect', $cdn_js_folder . '/bootstrap-multiselect.0.9.13.min.js', array('bootstrap'));
                $cmswebplatformapi->registerScript('daterangepicker', $cdn_js_folder . '/daterangepicker.2.1.24.min.js', array('bootstrap'), null);
            }
            $cmswebplatformapi->enqueueScript('bootstrap-multiselect');
            $cmswebplatformapi->enqueueScript('daterangepicker');
        
            if (PoP_WebPlatform_ServerUtils::useBundledResources()) {
                $cmswebplatformapi->registerScript('pop-bootstrapcollection-webplatform-templates', $bundles_js_folder . '/pop-bootstrapcollection.templates.bundle.min.js', array(), POP_BOOTSTRAPCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-bootstrapcollection-webplatform-templates');
                
                $cmswebplatformapi->registerScript('pop-bootstrapcollection-webplatform', $bundles_js_folder . '/pop-bootstrapcollection.bundle.min.js', array('jquery', 'jquery-ui-sortable'), POP_BOOTSTRAPCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-bootstrapcollection-webplatform');
            } else {
                $cmswebplatformapi->registerScript('pop-bootstrapcollection-bootstrapcontent', $libraries_js_folder.'/bootstrap-content'.$suffix.'.js', array('jquery', 'pop'), POP_BOOTSTRAPCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-bootstrapcollection-bootstrapcontent');

                $cmswebplatformapi->registerScript('pop-bootstrapcollection-bootstrapembed', $libraries_js_folder.'/bootstrap-embed'.$suffix.'.js', array('jquery', 'pop'), POP_BOOTSTRAPCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-bootstrapcollection-bootstrapembed');

                $cmswebplatformapi->registerScript('pop-bootstrapcollection-bootstrapfunctions', $libraries_js_folder.'/bootstrap-functions'.$suffix.'.js', array('jquery', 'pop'), POP_BOOTSTRAPCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-bootstrapcollection-bootstrapfunctions');

                $cmswebplatformapi->registerScript('pop-bootstrapcollection-bootstraptypeahead', $libraries_js_folder.'/bootstrap-typeahead'.$suffix.'.js', array('jquery', 'pop'), POP_BOOTSTRAPCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-bootstrapcollection-bootstraptypeahead');

                $cmswebplatformapi->registerScript('pop-bootstrapcollection-bootstraphooks', $libraries_js_folder.'/bootstrap-hooks'.$suffix.'.js', array('jquery', 'pop'), POP_BOOTSTRAPCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-bootstrapcollection-bootstraphooks');

                $cmswebplatformapi->registerScript('pop-bootstrapcollection-bootstrapinput', $libraries_js_folder.'/bootstrap-input'.$suffix.'.js', array('jquery', 'pop'), POP_BOOTSTRAPCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-bootstrapcollection-bootstrapinput');

                $cmswebplatformapi->registerScript('pop-bootstrapcollection-bootstrap-carousel', $libraries_js_folder.'/carousel/bootstrap-carousel'.$suffix.'.js', array('jquery', 'pop'), POP_BOOTSTRAPCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-bootstrapcollection-bootstrap-carousel');

                $cmswebplatformapi->registerScript('pop-bootstrapcollection-bootstrap-carousel-static', $libraries_js_folder.'/carousel/bootstrap-carousel-static'.$suffix.'.js', array('jquery', 'pop'), POP_BOOTSTRAPCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-bootstrapcollection-bootstrap-carousel-static');

                $cmswebplatformapi->registerScript('pop-bootstrapcollection-bootstrap-carousel-automatic', $libraries_js_folder.'/carousel/bootstrap-carousel-automatic'.$suffix.'.js', array('jquery', 'pop'), POP_BOOTSTRAPCOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-bootstrapcollection-bootstrap-carousel-automatic');

                // Templates
                $this->enqueueTemplatesScripts();
            }
        }
    }

    public function enqueueTemplatesScripts()
    {
        $cmswebplatformapi = \PoP\EngineWebPlatform\FunctionAPIFactory::getInstance();
        $folder = POP_BOOTSTRAPCOLLECTIONWEBPLATFORM_URL.'/js/dist/templates/';

        // All MESYM Theme Templates
        $cmswebplatformapi->enqueueScript('alert-tmpl', $folder.'alert.tmpl.js', array('handlebars'), POP_BOOTSTRAPCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('carousel-controls-tmpl', $folder.'carousel-controls.tmpl.js', array('handlebars'), POP_BOOTSTRAPCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('carousel-inner-tmpl', $folder.'carousel-inner.tmpl.js', array('handlebars'), POP_BOOTSTRAPCOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('carousel-tmpl', $folder.'carousel.tmpl.js', array('handlebars'), POP_BOOTSTRAPCOLLECTIONWEBPLATFORM_VERSION, true);
    }

    public function registerStyles()
    {
        $css_folder = POP_BOOTSTRAPCOLLECTIONWEBPLATFORM_URL.'/css';
        $dist_css_folder = $css_folder . '/dist';

        // Only if not doing code splitting then load the resources. Otherwise, the resources will be loaded by the ResourceLoader
        if (PoP_WebPlatform_ConfigurationUtils::registerScriptsAndStylesDuringInit()) {
            $htmlcssplatformapi = \PoP\EngineHTMLCSSPlatform\FunctionAPIFactory::getInstance();
            $includes_css_folder = $css_folder . '/includes';
            $cdn_css_folder = $includes_css_folder . '/cdn';
            $suffix = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? '.min' : '';

            if (PoP_WebPlatform_ServerUtils::accessExternalcdnResources()) {
                // CDN
                $htmlcssplatformapi->registerStyle('daterangepicker', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/2.1.24/daterangepicker.min.css', null, null);
            } else {
                // Locally stored files
                $htmlcssplatformapi->registerStyle('daterangepicker', $cdn_css_folder . '/daterangepicker.2.1.24.min.css', null, null);
            }
            $htmlcssplatformapi->enqueueStyle('daterangepicker');

            if (PoP_WebPlatform_ServerUtils::useBundledResources()) {
                $bundles_css_folder = $dist_css_folder . '/bundles';

                // Plug-in bundle
                $htmlcssplatformapi->registerStyle('pop-bootstrapcollection-webplatform', $bundles_css_folder . '/pop-bootstrapcollection.bundle.min.css', array('bootstrap'), POP_BOOTSTRAPCOLLECTIONWEBPLATFORM_VERSION);
                $htmlcssplatformapi->enqueueStyle('pop-bootstrapcollection-webplatform');
            } else {
                // Not in CDN
                $htmlcssplatformapi->registerStyle('bootstrap-multiselect', $includes_css_folder . '/bootstrap-multiselect.0.9.13'.$suffix.'.css', array('bootstrap'), POP_BOOTSTRAPCOLLECTIONWEBPLATFORM_VERSION, 'screen');
                $htmlcssplatformapi->enqueueStyle('bootstrap-multiselect');
            }
        }
    }
}
