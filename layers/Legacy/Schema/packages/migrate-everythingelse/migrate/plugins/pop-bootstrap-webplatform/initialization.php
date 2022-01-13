<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
class PoP_BootstrapWebPlatform_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-bootstrap-webplatform', false, dirname(plugin_basename(__FILE__)).'/languages');

        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        if (!$cmsapplicationapi->isAdminPanel()) {
            HooksAPIFacade::getInstance()->addAction('popcms:enqueueScripts', array($this, 'registerScripts'));
            HooksAPIFacade::getInstance()->addAction('popcms:printStyles', array($this, 'registerStyles'), 100);
        }

        /**
         * Load the Library
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
            $js_folder = POP_BOOTSTRAPWEBPLATFORM_URL.'/js';
            $dist_js_folder = $js_folder.'/dist';
            $libraries_js_folder = (PoP_WebPlatform_ServerUtils::useMinifiedResources() ? $dist_js_folder : $js_folder).'/libraries';
            $suffix = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? '.min' : '';
            $bundles_js_folder = $dist_js_folder.'/bundles';
            $includes_js_folder = $js_folder.'/includes';
            $cdn_js_folder = $includes_js_folder . '/cdn';

            // IMPORTANT: Don't change the order of enqueuing of files!
            // Register Bootstrap only after registering all other .js files which depend on jquery-ui, so bootstrap goes last in the Javascript stack
            // If before, it produces an error with $('btn').button('loading')
            // http://stackoverflow.com/questions/13235578/bootstrap-radio-buttons-toggle-issue
            
            if (PoP_WebPlatform_ServerUtils::accessExternalcdnResources()) {
                // Important: add dependency 'jquery-ui-dialog' to bootstrap. If not, when loading library 'fileupload' in pop-useravatar plug-in, it produces a JS error
                // Uncaught Error: cannot call methods on button prior to initialization; attempted to call method 'loading'

                // https://getbootstrap.com/getting-started/#download
                $cmswebplatformapi->registerScript('bootstrap', 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js', array('jquery', 'jquery-ui-dialog'), null);
            } else {
                $cmswebplatformapi->registerScript('bootstrap', $cdn_js_folder . '/bootstrap.3.3.7.min.js', array('jquery', 'jquery-ui-dialog'), null);
            }
            $cmswebplatformapi->enqueueScript('bootstrap');

            if (PoP_WebPlatform_ServerUtils::useBundledResources()) {
                $cmswebplatformapi->registerScript('pop-bootstrap-webplatform-templates', $bundles_js_folder . '/pop-bootstrap.templates.bundle.min.js', array(), POP_BOOTSTRAPWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-bootstrap-webplatform-templates');

                $cmswebplatformapi->registerScript('pop-bootstrap-webplatform', $bundles_js_folder . '/pop-bootstrap.bundle.min.js', array('jquery', 'jquery-ui-sortable'), POP_BOOTSTRAPWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-bootstrap-webplatform');
            } else {
                $cmswebplatformapi->registerScript('pop-custombootstrap', $libraries_js_folder.'/custombootstrap'.$suffix.'.js', array('jquery', 'pop', 'bootstrap'), POP_BOOTSTRAPWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-custombootstrap');

                $cmswebplatformapi->registerScript('pop-bootstrap-webplatform-bootstrap', $libraries_js_folder.'/bootstrap'.$suffix.'.js', array('jquery', 'pop', 'bootstrap'), POP_BOOTSTRAPWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-bootstrap-webplatform-bootstrap');

                $cmswebplatformapi->registerScript('pop-bootstrap-webplatform-modals', $libraries_js_folder.'/modals'.$suffix.'.js', array('jquery', 'pop', 'pop-bootstrap-webplatform-bootstrap'), POP_BOOTSTRAPWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-bootstrap-webplatform-modals');

                // Templates
                $this->enqueueTemplatesScripts();
            }
        }
    }

    public function enqueueTemplatesScripts()
    {
        $cmswebplatformapi = \PoP\EngineWebPlatform\FunctionAPIFactory::getInstance();
        $folder = POP_BOOTSTRAPWEBPLATFORM_URL.'/js/dist/templates/';

        $cmswebplatformapi->enqueueScript('carouselcomponent-tmpl', $folder.'carouselcomponent.tmpl.js', array('handlebars'), POP_BOOTSTRAPWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('collapsepanelgroup-tmpl', $folder.'collapsepanelgroup.tmpl.js', array('handlebars'), POP_BOOTSTRAPWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('modal-tmpl', $folder.'modal.tmpl.js', array('handlebars'), POP_BOOTSTRAPWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('tabpanel-tmpl', $folder.'tabpanel.tmpl.js', array('handlebars'), POP_BOOTSTRAPWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('viewcomponent-tmpl', $folder.'viewcomponent.tmpl.js', array('handlebars'), POP_BOOTSTRAPWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('pagesection-pagetab-tmpl', $folder.'pagesection-pagetab.tmpl.js', array('handlebars'), POP_BOOTSTRAPWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('pagesection-tabpane-tmpl', $folder.'pagesection-tabpane.tmpl.js', array('handlebars'), POP_BOOTSTRAPWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('pagesection-modal-tmpl', $folder.'pagesection-modal.tmpl.js', array('handlebars'), POP_BOOTSTRAPWEBPLATFORM_VERSION, true);
    }

    public function registerStyles()
    {

        // Only if not doing code splitting then load the resources. Otherwise, the resources will be loaded by the ResourceLoader
        if (PoP_WebPlatform_ConfigurationUtils::registerScriptsAndStylesDuringInit()) {
            $htmlcssplatformapi = \PoP\EngineHTMLCSSPlatform\FunctionAPIFactory::getInstance();
            $css_folder = POP_BOOTSTRAPWEBPLATFORM_URL.'/css';
            $includes_css_folder = $css_folder . '/includes';
            $cdn_css_folder = $includes_css_folder . '/cdn';

            /* ------------------------------
            * Wordpress Styles
            ----------------------------- */

            if (PoP_WebPlatform_ServerUtils::accessExternalcdnResources()) {
                // CDN
                $htmlcssplatformapi->registerStyle('bootstrap', 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css', null, null);
            } else {
                // Locally stored files
                $htmlcssplatformapi->registerStyle('bootstrap', $cdn_css_folder . '/bootstrap.3.3.7.min.css', null, null);
            }
            $htmlcssplatformapi->enqueueStyle('bootstrap');
        }
    }
}
