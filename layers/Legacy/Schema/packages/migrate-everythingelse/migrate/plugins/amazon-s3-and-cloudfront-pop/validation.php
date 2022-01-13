<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

define('AWSS3CFPOP_POP_CMSWP_MIN_VERSION', 0.1);
define('AWSS3CFPOP_AWSS3CF_MIN_VERSION', '1.2.1');

class AWSS3CFPoP_Validation
{
    public function validate()
    {
        $success = true;
        if (!defined('POP_ENGINEWP_VERSION')) {
            \PoP\Root\App::getHookManager()->addAction('admin_notices', array($this, 'installWarning'));
            \PoP\Root\App::getHookManager()->addAction('network_admin_notices', array($this, 'installWarning'));
            $success = false;
        } elseif (!defined('POP_ENGINEWP_INITIALIZED')) {
            \PoP\Root\App::getHookManager()->addAction('admin_notices', array($this, 'initializeWarning'));
            \PoP\Root\App::getHookManager()->addAction('network_admin_notices', array($this, 'initializeWarning'));
            $success = false;
        } elseif (AWSS3CFPOP_POP_CMSWP_MIN_VERSION > POP_ENGINEWP_VERSION) {
            \PoP\Root\App::getHookManager()->addAction('admin_notices', array($this, 'versionWarning'));
            \PoP\Root\App::getHookManager()->addAction('network_admin_notices', array($this, 'versionWarning'));
        }

        // Validate plug-in
        if (!function_exists('as3cf_init')) {
            \PoP\Root\App::getHookManager()->addAction('admin_notices', array($this,'pluginWarning'));
            \PoP\Root\App::getHookManager()->addAction('network_admin_notices', array($this,'pluginWarning'));
            $success = false;
        } elseif (AWSS3CFPOP_AWSS3CF_MIN_VERSION > $GLOBALS['aws_meta']['amazon-s3-and-cloudfront']['version']) {
            \PoP\Root\App::getHookManager()->addAction('admin_notices', array($this,'pluginversion_warning'));
            \PoP\Root\App::getHookManager()->addAction('network_admin_notices', array($this,'pluginversion_warning'));
        }

        return $success;
    }
    public function initializeWarning()
    {
        $this->dependencyInitializationWarning(
            TranslationAPIFacade::getInstance()->__('WP Offload S3 Lite for PoP', 'awss3cf-pop'),
            TranslationAPIFacade::getInstance()->__('PoP Engine for WordPress', 'awss3cf-pop')
        );
    }
    public function installWarning()
    {
        $this->dependencyInstallationWarning(
            TranslationAPIFacade::getInstance()->__('WP Offload S3 Lite for PoP', 'awss3cf-pop'),
            TranslationAPIFacade::getInstance()->__('PoP Engine for WordPress', 'awss3cf-pop'),
            'https://github.com/leoloso/PoP'
        );
    }
    public function versionWarning()
    {
        $this->dependencyVersionWarning(
            TranslationAPIFacade::getInstance()->__('WP Offload S3 Lite for PoP', 'awss3cf-pop'),
            TranslationAPIFacade::getInstance()->__('PoP Engine for WordPress', 'awss3cf-pop'),
            'https://github.com/leoloso/PoP',
            AWSS3CFPOP_POP_CMSWP_MIN_VERSION
        );
    }
    public function pluginWarning()
    {
        $this->dependencyInstallationWarning(
            TranslationAPIFacade::getInstance()->__('WP Offload S3 Lite for PoP', 'awss3cf-pop'),
            TranslationAPIFacade::getInstance()->__('WP Offload S3 Lite', 'awss3cf-pop'),
            'https://wordpress.org/plugins/amazon-s3-and-cloudfront/'
        );
    }
    public function pluginversion_warning()
    {
        $this->dependencyVersionWarning(
            TranslationAPIFacade::getInstance()->__('WP Offload S3 Lite for PoP', 'awss3cf-pop'),
            TranslationAPIFacade::getInstance()->__('WP Offload S3 Lite', 'awss3cf-pop'),
            'https://wordpress.org/plugins/amazon-s3-and-cloudfront/',
            AWSS3CFPOP_AWSS3CF_MIN_VERSION
        );
    }
    protected function dependencyInstallationWarning($plugin, $dependency, $dependency_url)
    {
        $this->adminNotice(
            sprintf(
                TranslationAPIFacade::getInstance()->__('Error: %s', 'pop-engine-webplatform'),
                sprintf(
                    TranslationAPIFacade::getInstance()->__('<strong>%s</strong> is not installed/activated. Without it, <strong>%s</strong> will not work. Please install this plugin from your plugin installer or download it <a href="%s" target="_blank">from here</a>.', 'pop-engine-webplatform'),
                    $dependency,
                    $plugin,
                    $dependency_url
                )
            )
        );
    }
    protected function dependencyInitializationWarning($plugin, $dependency)
    {
        $this->adminNotice(
            sprintf(
                TranslationAPIFacade::getInstance()->__('Error: %s', 'pop-engine-webplatform'),
                sprintf(
                    TranslationAPIFacade::getInstance()->__('<strong>%s</strong> is not initialized properly. As a consequence, <strong>%s</strong> has not been loaded.', 'pop-engine-webplatform'),
                    $dependency,
                    $plugin
                )
            )
        );
    }
    protected function dependencyVersionWarning($plugin, $dependency, $dependency_url, $dependency_min_version)
    {
        $this->adminNotice(
            sprintf(
                TranslationAPIFacade::getInstance()->__('Error: %s', 'pop-engine-webplatform'),
                sprintf(
                    TranslationAPIFacade::getInstance()->__('<strong>%s</strong> requires version %s or bigger of <strong>%s</strong>. Please update this plugin from your plugin installer or download it <a href="%s" target="_blank">from here</a>.', 'pop-engine-webplatform'),
                    $plugin,
                    $dependency_min_version,
                    $dependency,
                    $dependency_url
                )
            )
        );
    }
    protected function adminNotice($message)
    {
        ?>
        <div class="error">
            <p>
        <?php echo $message ?><br/>
                <em>
        <?php _e('Only admins see this message.', 'pop-engine-webplatform'); ?>
                </em>
            </p>
        </div>
        <?php
    }
}
