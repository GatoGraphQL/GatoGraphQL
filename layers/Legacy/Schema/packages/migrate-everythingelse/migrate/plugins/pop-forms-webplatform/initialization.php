<?php
class PoP_FormsWebPlatform_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-forms-webplatform', false, dirname(plugin_basename(__FILE__)).'/languages');

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
            $js_folder = POP_FORMSWEBPLATFORM_URL.'/js';
            $dist_js_folder = $js_folder.'/dist';
            $libraries_js_folder = (PoP_WebPlatform_ServerUtils::useMinifiedResources() ? $dist_js_folder : $js_folder).'/libraries';
            $suffix = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? '.min' : '';
            $bundles_js_folder = $dist_js_folder.'/bundles';

            if (PoP_WebPlatform_ServerUtils::useBundledResources()) {
                // $cmswebplatformapi->registerScript('pop-forms-webplatform-templates', $bundles_js_folder . '/pop-forms.templates.bundle.min.js', array(), POP_FORMSWEBPLATFORM_VERSION, true);
                // $cmswebplatformapi->enqueueScript('pop-forms-webplatform-templates');

                $cmswebplatformapi->registerScript('pop-forms-webplatform', $bundles_js_folder . '/pop-forms.bundle.min.js', array('pop', 'jquery'), POP_FORMSWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-forms-webplatform');
            } else {

                /**

       * Theme JS Sources
*/
                $cmswebplatformapi->registerScript('pop-forms-webplatform-helpers-handlebars-forms', $libraries_js_folder.'/handlebars-helpers/formcomponents'.$suffix.'.js', array('handlebars'), POP_FORMSWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-forms-webplatform-helpers-handlebars-forms');

                $cmswebplatformapi->registerScript('pop-helpers-handlebars-formatvalue', $libraries_js_folder.'/handlebars-helpers/formatvalue'.$suffix.'.js', array('handlebars'), POP_FORMSWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-helpers-handlebars-formatvalue');

                $cmswebplatformapi->registerScript('pop-forms-forms', $libraries_js_folder.'/forms'.$suffix.'.js', array('jquery', 'pop'), POP_FORMSWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-forms-forms');

                

                // Templates
                $this->enqueueTemplatesScripts();
            }
        }
    }

    public function enqueueTemplatesScripts()
    {
        $cmswebplatformapi = \PoP\EngineWebPlatform\FunctionAPIFactory::getInstance();
        $folder = POP_FORMSWEBPLATFORM_URL.'/js/dist/templates/';
        
        $cmswebplatformapi->enqueueScript('form-inner-tmpl', $folder.'form-inner.tmpl.js', array('handlebars'), POP_FORMSWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('form-tmpl', $folder.'form.tmpl.js', array('handlebars'), POP_FORMSWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('forminput-daterange-tmpl', $folder.'forminput-daterange.tmpl.js', array('handlebars'), POP_FORMSWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('forminput-editor-tmpl', $folder.'forminput-editor.tmpl.js', array('handlebars'), POP_FORMSWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('formcomponent-inputgroup-tmpl', $folder.'formcomponent-inputgroup.tmpl.js', array('handlebars'), POP_FORMSWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('forminput-buttongroup-tmpl', $folder.'forminput-buttongroup.tmpl.js', array('handlebars'), POP_FORMSWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('forminput-select-tmpl', $folder.'forminput-select.tmpl.js', array('handlebars'), POP_FORMSWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('forminput-checkbox-tmpl', $folder.'forminput-checkbox.tmpl.js', array('handlebars'), POP_FORMSWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('forminput-text-tmpl', $folder.'forminput-text.tmpl.js', array('handlebars'), POP_FORMSWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('forminput-textarea-tmpl', $folder.'forminput-textarea.tmpl.js', array('handlebars'), POP_FORMSWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('formcomponent-typeahead-selectable-tmpl', $folder.'formcomponent-typeahead-selectable.tmpl.js', array('handlebars'), POP_FORMSWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('formcomponentvalue-triggerlayout-tmpl', $folder.'formcomponentvalue-triggerlayout.tmpl.js', array('handlebars'), POP_FORMSWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('formgroup-tmpl', $folder.'formgroup.tmpl.js', array('handlebars'), POP_FORMSWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('extension-typeahead-suggestions-tmpl', $folder.'extension-typeahead-suggestions.tmpl.js', array('handlebars'), POP_FORMSWEBPLATFORM_VERSION, true);
    }
}
