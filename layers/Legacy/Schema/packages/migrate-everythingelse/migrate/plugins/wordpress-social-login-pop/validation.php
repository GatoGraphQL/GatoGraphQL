<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

define('WSLPOP_WSL_MIN_VERSION', '2.3.0');
define('WSLPOP_POP_CMSWP_MIN_VERSION', 0.1);

class WSL_PoP_Validation
{
    public function validate()
    {
        $success = true;
        if (!defined('POP_ENGINEWP_VERSION')) {
            HooksAPIFacade::getInstance()->addAction('admin_notices', array($this, 'installWarning'));
            HooksAPIFacade::getInstance()->addAction('network_admin_notices', array($this, 'installWarning'));
            $success = false;
        } elseif (!defined('POP_ENGINEWP_INITIALIZED')) {
            HooksAPIFacade::getInstance()->addAction('admin_notices', array($this, 'initializeWarning'));
            HooksAPIFacade::getInstance()->addAction('network_admin_notices', array($this, 'initializeWarning'));
            $success = false;
        } elseif (WSLPOP_POP_CMSWP_MIN_VERSION > POP_ENGINEWP_VERSION) {
            HooksAPIFacade::getInstance()->addAction('admin_notices', array($this, 'versionWarning'));
            HooksAPIFacade::getInstance()->addAction('network_admin_notices', array($this, 'versionWarning'));
        }

        global $WORDPRESS_SOCIAL_LOGIN_VERSION;

        // Validate plug-in
        if (!function_exists('wsl_activate')) {
            HooksAPIFacade::getInstance()->addAction('admin_notices', array($this,'pluginWarning'));
            HooksAPIFacade::getInstance()->addAction('network_admin_notices', array($this,'pluginWarning'));
            $success = false;
        } elseif (WSLPOP_WSL_MIN_VERSION > $WORDPRESS_SOCIAL_LOGIN_VERSION) {
            HooksAPIFacade::getInstance()->addAction('admin_notices', array($this,'pluginversion_warning'));
            HooksAPIFacade::getInstance()->addAction('network_admin_notices', array($this,'pluginversion_warning'));
        }

        return $success;
    }
    public function initializeWarning()
    {
        $this->dependencyInitializationWarning(
            TranslationAPIFacade::getInstance()->__('WordPress Social Login for PoP', 'wsl-pop'),
            TranslationAPIFacade::getInstance()->__('PoP Engine for WordPress', 'wsl-pop')
        );
    }
    public function installWarning()
    {
        $this->dependencyInstallationWarning(
            TranslationAPIFacade::getInstance()->__('WordPress Social Login for PoP', 'wsl-pop'),
            TranslationAPIFacade::getInstance()->__('PoP Engine for WordPress', 'wsl-pop'),
            'https://github.com/leoloso/PoP'
        );
    }
    public function versionWarning()
    {
        $this->dependencyVersionWarning(
            TranslationAPIFacade::getInstance()->__('WordPress Social Login for PoP', 'wsl-pop'),
            TranslationAPIFacade::getInstance()->__('PoP Engine for WordPress', 'wsl-pop'),
            'https://github.com/leoloso/PoP',
            WSLPOP_POP_CMSWP_MIN_VERSION
        );
    }
    public function pluginWarning()
    {
        $this->dependencyInstallationWarning(
            TranslationAPIFacade::getInstance()->__('WordPress Social Login for PoP', 'wsl-pop'),
            TranslationAPIFacade::getInstance()->__('WordPress Social Login', 'wsl-pop'),
            'https://wordpress.org/plugins/wordpress-social-login/'
        );
    }
    public function pluginversion_warning()
    {
        $this->dependencyVersionWarning(
            TranslationAPIFacade::getInstance()->__('WordPress Social Login for PoP', 'wsl-pop'),
            TranslationAPIFacade::getInstance()->__('WordPress Social Login', 'wsl-pop'),
            'https://wordpress.org/plugins/wordpress-social-login/',
            WSLPOP_WSL_MIN_VERSION
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
