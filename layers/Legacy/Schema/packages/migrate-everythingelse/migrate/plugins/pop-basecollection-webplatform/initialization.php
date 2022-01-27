<?php
class PoP_BaseCollectionWebPlatform_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-basecollection-webplatform', false, dirname(plugin_basename(__FILE__)).'/languages');

        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        if (!$cmsapplicationapi->isAdminPanel()) {
            \PoP\Root\App::addAction('popcms:enqueueScripts', array($this, 'registerScripts'));
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
            $js_folder = POP_BASECOLLECTIONWEBPLATFORM_URL.'/js';
            $dist_js_folder = $js_folder.'/dist';
            $bundles_js_folder = $dist_js_folder.'/bundles';
            $includes_js_folder = $js_folder.'/includes';
            $cdn_js_folder = $includes_js_folder . '/cdn';

            if (PoP_WebPlatform_ServerUtils::accessExternalcdnResources()) {
                // https://github.com/moment/moment/releases
                $cmswebplatformapi->registerScript('moment', 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1/moment.min.js', array('jquery'), null);
            } else {
                $cmswebplatformapi->registerScript('moment', $cdn_js_folder . '/moment.2.15.1.min.js', array('jquery'), null);
            }
            $cmswebplatformapi->enqueueScript('moment');
            
            
            if (PoP_WebPlatform_ServerUtils::useBundledResources()) {
                $cmswebplatformapi->registerScript('pop-basecollection-webplatform-templates', $bundles_js_folder . '/pop-basecollection.templates.bundle.min.js', array(), POP_BASECOLLECTIONWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-basecollection-webplatform-templates');
            } else {

                // Templates
                $this->enqueueTemplatesScripts();
            }
        }
    }

    public function enqueueTemplatesScripts()
    {
        $cmswebplatformapi = \PoP\EngineWebPlatform\FunctionAPIFactory::getInstance();
        $folder = POP_BASECOLLECTIONWEBPLATFORM_URL.'/js/dist/templates/';

        $cmswebplatformapi->enqueueScript('block-tmpl', $folder.'block.tmpl.js', array('handlebars'), POP_BASECOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('basicblock-tmpl', $folder.'basicblock.tmpl.js', array('handlebars'), POP_BASECOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('pagesection-plain-tmpl', $folder.'pagesection-plain.tmpl.js', array('handlebars'), POP_BASECOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('content-tmpl', $folder.'content.tmpl.js', array('handlebars'), POP_BASECOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('contentmultiple-inner-tmpl', $folder.'contentmultiple-inner.tmpl.js', array('handlebars'), POP_BASECOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('contentsingle-inner-tmpl', $folder.'contentsingle-inner.tmpl.js', array('handlebars'), POP_BASECOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('nocontent-tmpl', $folder.'nocontent.tmpl.js', array('handlebars'), POP_BASECOLLECTIONWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('multiple-tmpl', $folder.'multiple.tmpl.js', array('handlebars'), POP_BASECOLLECTIONWEBPLATFORM_VERSION, true);
    }
}
