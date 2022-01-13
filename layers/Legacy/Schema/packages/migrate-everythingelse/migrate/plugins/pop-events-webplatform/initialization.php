<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
class PoP_EventsWebPlatform_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-events-webplatform', false, dirname(plugin_basename(__FILE__)).'/languages');

        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        if (!$cmsapplicationapi->isAdminPanel()) {
            \PoP\Root\App::getHookManager()->addAction('popcms:enqueueScripts', array($this, 'registerScripts'));
            \PoP\Root\App::getHookManager()->addAction('popcms:printStyles', array($this, 'registerStyles'), 100);
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
            $js_folder = POP_EVENTSWEBPLATFORM_URL.'/js';
            $dist_js_folder = $js_folder.'/dist';
            $libraries_js_folder = (PoP_WebPlatform_ServerUtils::useMinifiedResources() ? $dist_js_folder : $js_folder).'/libraries';
            $suffix = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? '.min' : '';
            $bundles_js_folder = $dist_js_folder.'/bundles';

            if (PoP_WebPlatform_ServerUtils::accessExternalcdnResources()) {
                $cmswebplatformapi->registerScript('fullcalendar', 'https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.8.2/fullcalendar.min.js', array('jquery', 'moment'), null);
            } else {
                $cdn_folder = $js_folder . '/includes/cdn';
                $cmswebplatformapi->registerScript('fullcalendar', $cdn_folder . '/fullcalendar.3.8.2.min.js', array('jquery', 'moment'), null);
            }
            $cmswebplatformapi->enqueueScript('fullcalendar');

            if (PoP_WebPlatform_ServerUtils::useBundledResources()) {
                $cmswebplatformapi->registerScript('pop-events-webplatform-templates', $bundles_js_folder . '/pop-events.templates.bundle.min.js', array(), POP_EVENTSWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-events-webplatform-templates');

                $cmswebplatformapi->registerScript('pop-events-webplatform', $bundles_js_folder . '/pop-events.bundle.min.js', array('pop', 'jquery'), POP_EVENTSWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-events-webplatform');
            } else {
                $cmswebplatformapi->registerScript('pop-events-webplatform-fullcalendar', $libraries_js_folder.'/3rdparties/calendar/fullcalendar'.$suffix.'.js', array('jquery', 'pop'), POP_EVENTSWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-events-webplatform-fullcalendar');

                $cmswebplatformapi->registerScript('pop-events-webplatform-fullcalendar-memory', $libraries_js_folder.'/3rdparties/calendar/fullcalendar-memory'.$suffix.'.js', array('jquery', 'pop'), POP_EVENTSWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-events-webplatform-fullcalendar-memory');

                $cmswebplatformapi->registerScript('pop-events-webplatform-fullcalendar-addevents', $libraries_js_folder.'/3rdparties/calendar/fullcalendar-addevents'.$suffix.'.js', array('jquery', 'pop'), POP_EVENTSWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-events-webplatform-fullcalendar-addevents');

                // Templates
                $this->enqueueTemplatesScripts();
            }
        }
    }

    public function enqueueTemplatesScripts()
    {
        $cmswebplatformapi = \PoP\EngineWebPlatform\FunctionAPIFactory::getInstance();
        $folder = POP_EVENTSWEBPLATFORM_URL.'/js/dist/templates/';

        $cmswebplatformapi->enqueueScript('em-calendar-inner-tmpl', $folder.'em-calendar-inner.tmpl.js', array('handlebars'), POP_EVENTSWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('em-calendar-tmpl', $folder.'em-calendar.tmpl.js', array('handlebars'), POP_EVENTSWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('em-layoutcalendar-content-tmpl', $folder.'em-layoutcalendar-content.tmpl.js', array('handlebars'), POP_EVENTSWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('em-layout-datetime-tmpl', $folder.'em-layout-datetime.tmpl.js', array('handlebars'), POP_EVENTSWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('em-layout-carousel-indicators-eventdate-tmpl', $folder.'em-layout-carousel-indicators-eventdate.tmpl.js', array('handlebars'), POP_EVENTSWEBPLATFORM_VERSION, true);
    }

    public function registerStyles()
    {

        /* ------------------------------
        * 3rd Party Libraries (using CDN whenever possible)
        ----------------------------- */

        // Only if not doing code splitting then load the resources. Otherwise, the resources will be loaded by the ResourceLoader
        if (PoP_WebPlatform_ConfigurationUtils::registerScriptsAndStylesDuringInit()) {
            $htmlcssplatformapi = \PoP\EngineHTMLCSSPlatform\FunctionAPIFactory::getInstance();
            $css_folder = POP_EVENTSWEBPLATFORM_URL.'/css';
            $includes_css_folder = $css_folder . '/includes';
            $cdn_css_folder = $includes_css_folder . '/cdn';

            if (PoP_WebPlatform_ServerUtils::accessExternalcdnResources()) {
                // CDN
                $htmlcssplatformapi->registerStyle('fullcalendar', 'https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.8.2/fullcalendar.min.css', null, null);
            } else {
                // Locally stored files
                $htmlcssplatformapi->registerStyle('fullcalendar', $cdn_css_folder . '/fullcalendar.3.8.2.min.css', null, null);
            }
            $htmlcssplatformapi->enqueueStyle('fullcalendar');
        }
    }
}
