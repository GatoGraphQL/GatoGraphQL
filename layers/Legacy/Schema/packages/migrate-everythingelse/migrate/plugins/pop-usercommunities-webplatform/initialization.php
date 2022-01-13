<?php
class PoP_UserCommunitiesWebPlatform_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-usercommunities-webplatform', false, dirname(plugin_basename(__FILE__)).'/languages');

        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        if (!$cmsapplicationapi->isAdminPanel()) {
            \PoP\Root\App::getHookManager()->addAction('popcms:enqueueScripts', array($this, 'registerScripts'));
        }

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
            $js_folder = POP_USERCOMMUNITIESWEBPLATFORM_URL.'/js';
            $dist_js_folder = $js_folder.'/dist';
            $libraries_js_folder = (PoP_WebPlatform_ServerUtils::useMinifiedResources() ? $dist_js_folder : $js_folder).'/libraries';
            $suffix = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? '.min' : '';
            $bundles_js_folder = $dist_js_folder.'/bundles';

            if (PoP_WebPlatform_ServerUtils::useBundledResources()) {
                $cmswebplatformapi->registerScript('pop-usercommunities-webplatform', $bundles_js_folder . '/pop-usercommunities.bundle.min.js', array(), POP_USERCOMMUNITIESWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-usercommunities-webplatform');
                
                $cmswebplatformapi->registerScript('pop-usercommunities-webplatform-templates', $bundles_js_folder . '/pop-usercommunities.templates.bundle.min.js', array(), POP_USERCOMMUNITIESWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-usercommunities-webplatform-templates');
            } else {
                $cmswebplatformapi->registerScript('pop-usercommunitieswebplatform-user-roles-account', $libraries_js_folder.'/user-communities-account'.$suffix.'.js', array('jquery', 'pop'), POP_USERCOMMUNITIESWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-usercommunitieswebplatform-user-roles-account');

                $cmswebplatformapi->registerScript('ure-popprocessors-aal-functions', $libraries_js_folder.'/community-notifications'.$suffix.'.js', array('jquery', 'pop'), POP_USERCOMMUNITIESWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('ure-popprocessors-aal-functions');

                // Templates
                $this->enqueueTemplatesScripts();
            }
        }
    }

    public function enqueueTemplatesScripts()
    {
        $cmswebplatformapi = \PoP\EngineWebPlatform\FunctionAPIFactory::getInstance();
        $folder = POP_USERCOMMUNITIESWEBPLATFORM_URL.'/js/dist/templates/';

        $cmswebplatformapi->enqueueScript('layoutuser-memberstatus-tmpl', $folder.'layoutuser-memberstatus.tmpl.js', array('handlebars'), POP_USERCOMMUNITIESWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('layoutuser-memberprivileges-tmpl', $folder.'layoutuser-memberprivileges.tmpl.js', array('handlebars'), POP_USERCOMMUNITIESWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('layoutuser-membertags-tmpl', $folder.'layoutuser-membertags.tmpl.js', array('handlebars'), POP_USERCOMMUNITIESWEBPLATFORM_VERSION, true);
    }
}
