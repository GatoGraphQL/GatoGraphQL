<?php
class PoP_UserAvatarWebPlatform_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-useravatar-webplatform', false, dirname(plugin_basename(__FILE__)).'/languages');

        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        if (!$cmsapplicationapi->isAdminPanel()) {
            \PoP\Root\App::getHookManager()->addAction('popcms:enqueueScripts', array($this, 'registerScripts'));
            \PoP\Root\App::getHookManager()->addAction('popcms:printStyles', array($this, 'registerStyles'));
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
            $js_folder = POP_USERAVATARWEBPLATFORM_URL.'/js';
            $includes_js_folder = $js_folder.'/includes';
            $cdn_js_folder = $includes_js_folder . '/cdn';
            $dist_js_folder = $js_folder.'/dist';
            $libraries_js_folder = (PoP_WebPlatform_ServerUtils::useMinifiedResources() ? $dist_js_folder : $js_folder).'/libraries';
            $suffix = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? '.min' : '';
            $bundles_js_folder = $dist_js_folder.'/bundles';

            if (PoP_WebPlatform_ServerUtils::accessExternalcdnResources()) {
                // File Upload
                $cmswebplatformapi->registerScript('fileupload-iframe-transport', 'https://cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.5.7/jquery.iframe-transport.min.js', null, null);
                $cmswebplatformapi->registerScript('fileupload', 'https://cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.5.7/jquery.fileupload.min.js', array('jquery-ui-dialog', 'fileupload-iframe-transport'), null);
                $cmswebplatformapi->registerScript('fileupload-ui', 'https://cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.5.7/jquery.fileupload-ui.min.js', array('fileupload'), null);
                $cmswebplatformapi->registerScript('fileupload-process', 'https://cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.5.7/jquery.fileupload-process.min.js', array('fileupload'), null);
                $cmswebplatformapi->registerScript('fileupload-validate', 'https://cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.5.7/jquery.fileupload-validate.min.js', array('fileupload'), null);
            } else {
                // File Upload
                $cmswebplatformapi->registerScript('fileupload-iframe-transport', $cdn_js_folder . '/jquery.iframe-transport.9.5.7.min.js', null, null);
                $cmswebplatformapi->registerScript('fileupload', $cdn_js_folder . '/jquery.fileupload.9.5.7.min.js', array('jquery-ui-dialog', 'fileupload-iframe-transport'), null);
                $cmswebplatformapi->registerScript('fileupload-ui', $cdn_js_folder . '/jquery.fileupload-ui.9.5.7.min.js', array('fileupload'), null);
                $cmswebplatformapi->registerScript('fileupload-process', $cdn_js_folder . '/jquery.fileupload-process.9.5.7.min.js', array('fileupload'), null);
                $cmswebplatformapi->registerScript('fileupload-validate', $cdn_js_folder . '/jquery.fileupload-validate.9.5.7.min.js', array('fileupload'), null);
            }

            // File Upload
            $cmswebplatformapi->enqueueScript('fileupload-iframe-transport');
            $cmswebplatformapi->enqueueScript('fileupload');
            $cmswebplatformapi->enqueueScript('fileupload-ui');
            $cmswebplatformapi->enqueueScript('fileupload-process');
            $cmswebplatformapi->enqueueScript('fileupload-validate');
            
            $cmswebplatformapi->registerScript('fileupload-locale', popUseravatarGetLocaleJsfile(), array('fileupload'), null);
            $cmswebplatformapi->enqueueScript('fileupload-locale');

            if (PoP_WebPlatform_ServerUtils::useBundledResources()) {
                $cmswebplatformapi->registerScript('pop-useravatar-webplatform-templates', $bundles_js_folder . '/pop-useravatar.templates.bundle.min.js', array(), POP_USERAVATARWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-useravatar-webplatform-templates');
                
                $cmswebplatformapi->registerScript('pop-useravatar-webplatform', $bundles_js_folder . '/pop-useravatar.bundle.min.js', array(), POP_USERAVATARWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-useravatar-webplatform');
            } else {
                $cmswebplatformapi->registerScript('pop-useravatarwebplatform-user-avatar-account', $libraries_js_folder.'/user-avatar-account'.$suffix.'.js', array('jquery', 'pop'), POP_USERAVATARWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-useravatarwebplatform-user-avatar-account');

                $cmswebplatformapi->registerScript('pop-useravatar-processors-fileupload', $libraries_js_folder.'/fileupload'.$suffix.'.js', array('jquery', 'pop'), POP_USERAVATARWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-useravatar-processors-fileupload');

                // Templates
                $this->enqueueTemplatesScripts();
            }
        }
    }

    public function enqueueTemplatesScripts()
    {
        $cmswebplatformapi = \PoP\EngineWebPlatform\FunctionAPIFactory::getInstance();
        $folder = POP_USERAVATARWEBPLATFORM_URL.'/js/dist/templates/';

        $cmswebplatformapi->enqueueScript('layout-loggedin-user-avatar-tmpl', $folder.'layout-loggedin-user-avatar.tmpl.js', array('handlebars'), POP_USERAVATARWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('fileupload-picture-download-tmpl', $folder.'fileupload-picture-download.tmpl.js', array('handlebars'), POP_USERAVATARWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('fileupload-picture-upload-tmpl', $folder.'fileupload-picture-upload.tmpl.js', array('handlebars'), POP_USERAVATARWEBPLATFORM_VERSION, true);
        $cmswebplatformapi->enqueueScript('fileupload-picture-tmpl', $folder.'fileupload-picture.tmpl.js', array('handlebars'), POP_USERAVATARWEBPLATFORM_VERSION, true);
    }
    public function registerStyles()
    {

        // Only if not doing code splitting then load the resources. Otherwise, the resources will be loaded by the ResourceLoader
        if (PoP_WebPlatform_ConfigurationUtils::registerScriptsAndStylesDuringInit()) {
            $htmlcssplatformapi = \PoP\EngineHTMLCSSPlatform\FunctionAPIFactory::getInstance();
            $css_folder = POP_USERAVATARWEBPLATFORM_URL.'/css';
            $includes_css_folder = $css_folder . '/includes';
            $cdn_css_folder = $includes_css_folder . '/cdn';

            /* ------------------------------
            * 3rd Party Libraries (using CDN whenever possible)
            ----------------------------- */

            if (PoP_WebPlatform_ServerUtils::accessExternalcdnResources()) {
                // CDN
                $htmlcssplatformapi->registerStyle('fileupload', 'https://cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.5.7/css/jquery.fileupload.min.css', null, null);
            } else {
                // Locally stored files
                $htmlcssplatformapi->registerStyle('fileupload', $cdn_css_folder . '/jquery.fileupload.9.5.7.min.css', null, null);
            }
            $htmlcssplatformapi->enqueueStyle('fileupload');
        }
    }
}
