<?php
class PoP_LocationsWebPlatform_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-locations-webplatform', false, dirname(plugin_basename(__FILE__)).'/languages');

        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        if (!$cmsapplicationapi->isAdminPanel()) {
            \PoP\Root\App::getHookManager()->addAction('popcms:enqueueScripts', array($this, 'registerScripts'));
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
            $js_folder = POP_LOCATIONSWEBPLATFORM_URL.'/js';
            $dist_js_folder = $js_folder.'/dist';
            $libraries_js_folder = (PoP_WebPlatform_ServerUtils::useMinifiedResources() ? $dist_js_folder : $js_folder).'/libraries';
            $suffix = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? '.min' : '';
            $bundles_js_folder = $dist_js_folder.'/bundles';

            if (PoP_WebPlatform_ServerUtils::useBundledResources()) {
                $cmswebplatformapi->registerScript('pop-locations-webplatform-templates', $bundles_js_folder . '/pop-locations.templates.bundle.min.js', array(), POP_LOCATIONSWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-locations-webplatform-templates');

                $cmswebplatformapi->registerScript('pop-locations-webplatform', $bundles_js_folder . '/pop-locations.bundle.min.js', array('pop', 'jquery'), POP_LOCATIONSWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-locations-webplatform');
            } else {
                $cmswebplatformapi->registerScript('pop-locations-webplatform-map', $libraries_js_folder.'/map/map'.$suffix.'.js', array('jquery', 'pop'), POP_LOCATIONSWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-locations-webplatform-map');

                $cmswebplatformapi->registerScript('pop-locations-webplatform-map-memory', $libraries_js_folder.'/map/map-memory'.$suffix.'.js', array('jquery', 'pop'), POP_LOCATIONSWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-locations-webplatform-map-memory');

                $cmswebplatformapi->registerScript('pop-locations-webplatform-map-initmarker', $libraries_js_folder.'/map/map-initmarker'.$suffix.'.js', array('jquery', 'pop'), POP_LOCATIONSWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-locations-webplatform-map-initmarker');

                $cmswebplatformapi->registerScript('pop-locations-webplatform-map-runtime', $libraries_js_folder.'/map/map-runtime'.$suffix.'.js', array('jquery', 'pop'), POP_LOCATIONSWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-locations-webplatform-map-runtime');

                $cmswebplatformapi->registerScript('pop-locations-webplatform-map-runtime-memory', $libraries_js_folder.'/map/map-runtime-memory'.$suffix.'.js', array('jquery', 'pop'), POP_LOCATIONSWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-locations-webplatform-map-runtime-memory');

                $cmswebplatformapi->registerScript('pop-locations-webplatform-selectabletypeaheadmap', $libraries_js_folder.'/typeahead-map-selectable'.$suffix.'.js', array('jquery', 'pop', 'pop-locations-webplatform-map', 'pop-mastercollection-typeahead'), POP_LOCATIONSWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-locations-webplatform-selectabletypeaheadmap');

                $cmswebplatformapi->registerScript('pop-locations-webplatform-mapcollection', $libraries_js_folder.'/map-collection'.$suffix.'.js', array('jquery', 'pop', 'pop-locations-webplatform-map'), POP_LOCATIONSWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-locations-webplatform-mapcollection');

                if (defined('POP_BOOTSTRAPPROCESSORS_INITIALIZED')) {
                    $cmswebplatformapi->registerScript('pop-locations-webplatform-bootstrap-mapcollection', $libraries_js_folder.'/bootstrap/bootstrap-map-collection'.$suffix.'.js', array('jquery', 'pop', 'pop-locations-webplatform-map', 'pop-locations-webplatform-mapcollection'), POP_LOCATIONSWEBPLATFORM_VERSION, true);
                    $cmswebplatformapi->enqueueScript('pop-locations-webplatform-bootstrap-mapcollection');

                    $cmswebplatformapi->registerScript('pop-locations-webplatform-bootstrap-map', $libraries_js_folder.'/bootstrap/bootstrap-map'.$suffix.'.js', array('jquery', 'pop', 'pop-locations-webplatform-map'), POP_LOCATIONSWEBPLATFORM_VERSION, true);
                    $cmswebplatformapi->enqueueScript('pop-locations-webplatform-bootstrap-map');
                }

                // Templates
                $this->enqueueTemplatesScripts();
            }
        }
    }

    public function enqueueTemplatesScripts()
    {
        $cmswebplatformapi = \PoP\EngineWebPlatform\FunctionAPIFactory::getInstance();
        $folder = POP_LOCATIONSWEBPLATFORM_URL.'/js/dist/templates/';

        $cmswebplatformapi->enqueueScript('em-map', $folder.'em-map.tmpl.js', array('handlebars'), POP_LOCATIONSWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('em-map-inner', $folder.'em-map-inner.tmpl.js', array('handlebars'), POP_LOCATIONSWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('em-map-script-tmpl', $folder.'em-map-script.tmpl.js', array('handlebars'), POP_LOCATIONSWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('em-map-individual-tmpl', $folder.'em-map-individual.tmpl.js', array('handlebars'), POP_LOCATIONSWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('em-post-map-scriptcustomization-tmpl', $folder.'em-post-map-scriptcustomization.tmpl.js', array('handlebars'), POP_LOCATIONSWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('em-user-map-scriptcustomization-tmpl', $folder.'em-user-map-scriptcustomization.tmpl.js', array('handlebars'), POP_LOCATIONSWEBPLATFORM_VERSION, true);

        $cmswebplatformapi->enqueueScript('em-map-addmarker-tmpl', $folder.'em-map-addmarker.tmpl.js', array('handlebars'), POP_LOCATIONSWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('em-map-addmarker-tmpl', $folder.'em-map-addmarker.tmpl.js', array('handlebars'), POP_LOCATIONSWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('em-map-div-tmpl', $folder.'em-map-div.tmpl.js', array('handlebars'), POP_LOCATIONSWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('em-map-script-drawmarkers-tmpl', $folder.'em-map-script-drawmarkers.tmpl.js', array('handlebars'), POP_LOCATIONSWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('em-map-script-resetmarkers-tmpl', $folder.'em-map-script-resetmarkers.tmpl.js', array('handlebars'), POP_LOCATIONSWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('em-map-script-markers-tmpl', $folder.'em-map-script-markers.tmpl.js', array('handlebars'), POP_LOCATIONSWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('em-map-staticimage-tmpl', $folder.'em-map-staticimage.tmpl.js', array('handlebars'), POP_LOCATIONSWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('em-map-staticimage-urlparam-tmpl', $folder.'em-map-staticimage-urlparam.tmpl.js', array('handlebars'), POP_LOCATIONSWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('em-map-staticimage-locations-tmpl', $folder.'em-map-staticimage-locations.tmpl.js', array('handlebars'), POP_LOCATIONSWEBPLATFORM_VERSION, true);
        
        $cmswebplatformapi->enqueueScript('em-layoutlocation-typeahead-component-tmpl', $folder.'em-layoutlocation-typeahead-component.tmpl.js', array('handlebars'), POP_LOCATIONSWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('em-layoutlocation-card-tmpl', $folder.'em-layoutlocation-card.tmpl.js', array('handlebars'), POP_LOCATIONSWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('em-layout-locations-tmpl', $folder.'em-layout-locations.tmpl.js', array('handlebars'), POP_LOCATIONSWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('em-layout-locationname-tmpl', $folder.'em-layout-locationname.tmpl.js', array('handlebars'), POP_LOCATIONSWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('em-layout-locationaddress-tmpl', $folder.'em-layout-locationaddress.tmpl.js', array('handlebars'), POP_LOCATIONSWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('em-script-triggertypeaheadselect-location-tmpl', $folder.'em-script-triggertypeaheadselect-location.tmpl.js', array('handlebars'), POP_LOCATIONSWEBPLATFORM_VERSION, true);

        $cmswebplatformapi->enqueueScript('em-viewcomponent-locationlink-tmpl', $folder.'em-viewcomponent-locationlink.tmpl.js', array('handlebars'), POP_LOCATIONSWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('em-viewcomponent-locationbutton-tmpl', $folder.'em-viewcomponent-locationbutton.tmpl.js', array('handlebars'), POP_LOCATIONSWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('em-viewcomponent-locationbuttoninner-tmpl', $folder.'em-viewcomponent-locationbuttoninner.tmpl.js', array('handlebars'), POP_LOCATIONSWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('em-formcomponent-typeaheadmap-tmpl', $folder.'em-formcomponent-typeaheadmap.tmpl.js', array('handlebars'), POP_LOCATIONSWEBPLATFORM_VERSION, true);
    }
}
