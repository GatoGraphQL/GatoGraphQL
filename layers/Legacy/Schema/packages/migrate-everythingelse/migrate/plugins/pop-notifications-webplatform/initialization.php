<?php
class PoP_NotificationsWebPlatform_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-notifications-webplatform', false, dirname(plugin_basename(__FILE__)).'/languages');

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
            $js_folder = POP_NOTIFICATIONSWEBPLATFORM_URL.'/js';
            $dist_js_folder = $js_folder.'/dist';
            $libraries_js_folder = (PoP_WebPlatform_ServerUtils::useMinifiedResources() ? $dist_js_folder : $js_folder).'/libraries';
            $suffix = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? '.min' : '';
            $bundles_js_folder = $dist_js_folder.'/bundles';

            if (PoP_WebPlatform_ServerUtils::useBundledResources()) {
                $cmswebplatformapi->registerScript('pop-notifications-webplatform-templates', $bundles_js_folder . '/pop-notifications.templates.bundle.min.js', array(), POP_NOTIFICATIONSWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-notifications-webplatform-templates');
                
                $cmswebplatformapi->registerScript('pop-notifications-webplatform', $bundles_js_folder . '/pop-notifications.bundle.min.js', array(), POP_NOTIFICATIONSWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-notifications-webplatform');
            } else {
                $cmswebplatformapi->registerScript('pop-notifications-notifications', $libraries_js_folder.'/notifications'.$suffix.'.js', array('jquery', 'pop'), POP_NOTIFICATIONSWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-notifications-notifications');

                // Templates
                $this->enqueueTemplatesScripts();
            }
        }
    }

    public function enqueueTemplatesScripts()
    {
        $cmswebplatformapi = \PoP\EngineWebPlatform\FunctionAPIFactory::getInstance();
        $folder = POP_NOTIFICATIONSWEBPLATFORM_URL.'/js/dist/templates/';

        $cmswebplatformapi->enqueueScript('layout-previewnotification-tmpl', $folder.'layout-previewnotification.tmpl.js', array('handlebars'), POP_NOTIFICATIONSWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('layout-notificationtime-tmpl', $folder.'layout-notificationtime.tmpl.js', array('handlebars'), POP_NOTIFICATIONSWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('layout-notificationicon-tmpl', $folder.'layout-notificationicon.tmpl.js', array('handlebars'), POP_NOTIFICATIONSWEBPLATFORM_VERSION, true);
    }
}
