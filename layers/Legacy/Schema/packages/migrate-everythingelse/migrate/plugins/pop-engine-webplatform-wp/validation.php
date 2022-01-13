<?php
namespace PoP\EngineWebPlatform;
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

define('POP_ENGINEWEBPLATFORMWP_POP_ENGINEWP_MIN_VERSION', 0.1);
define('POP_ENGINEWEBPLATFORMWP_POP_ENGINEWEBPLATFORM_MIN_VERSION', 0.1);

class Validation
{
    public function validate($validate_provided)
    {
        $success = true;
        if (!defined('POP_ENGINEWP_VERSION')) {
            HooksAPIFacade::getInstance()->addAction('admin_notices', array($this, 'install_warning'));
            HooksAPIFacade::getInstance()->addAction('network_admin_notices', array($this, 'install_warning'));
            $success = false;
        } elseif (!defined('POP_ENGINEWP_INITIALIZED')) {
            HooksAPIFacade::getInstance()->addAction('admin_notices', array($this, 'initialize_warning'));
            HooksAPIFacade::getInstance()->addAction('network_admin_notices', array($this, 'initialize_warning'));
            $success = false;
        } elseif (POP_ENGINEWEBPLATFORMWP_POP_ENGINEWP_MIN_VERSION > POP_ENGINEWP_VERSION) {
            HooksAPIFacade::getInstance()->addAction('admin_notices', array($this, 'version_warning'));
            HooksAPIFacade::getInstance()->addAction('network_admin_notices', array($this, 'version_warning'));
        }

        if ($validate_provided) {
            if (!defined('POP_ENGINEWEBPLATFORM_VERSION')) {
                HooksAPIFacade::getInstance()->addAction('admin_notices', array($this, 'install_warning_2'));
                HooksAPIFacade::getInstance()->addAction('network_admin_notices', array($this, 'install_warning_2'));
                $success = false;
            } elseif (!defined('POP_ENGINEWEBPLATFORM_INITIALIZED')) {
                HooksAPIFacade::getInstance()->addAction('admin_notices', array($this, 'initialize_warning_2'));
                HooksAPIFacade::getInstance()->addAction('network_admin_notices', array($this, 'initialize_warning_2'));
                $success = false;
            } elseif (POP_ENGINEWEBPLATFORMWP_POP_ENGINEWEBPLATFORM_MIN_VERSION > POP_ENGINEWEBPLATFORM_VERSION) {
                HooksAPIFacade::getInstance()->addAction('admin_notices', array($this, 'version_warning_2'));
                HooksAPIFacade::getInstance()->addAction('network_admin_notices', array($this, 'version_warning_2'));
            }
        }

        return $success;
    }
    public function initialize_warning()
    {
        $this->dependencyInitializationWarning(
            TranslationAPIFacade::getInstance()->__('PoP Web Platform Engine for WordPress', 'pop-engine-webplatform-wp'),
            TranslationAPIFacade::getInstance()->__('PoP Engine for WordPress', 'pop-engine-webplatform-wp')
        );
    }
    public function install_warning()
    {
        $this->dependencyInstallationWarning(
            TranslationAPIFacade::getInstance()->__('PoP Web Platform Engine for WordPress', 'pop-engine-webplatform-wp'),
            TranslationAPIFacade::getInstance()->__('PoP Engine for WordPress', 'pop-engine-webplatform-wp'),
            'https://github.com/leoloso/PoP'
        );
    }
    public function version_warning()
    {
        $this->dependencyVersionWarning(
            TranslationAPIFacade::getInstance()->__('PoP Web Platform Engine for WordPress', 'pop-engine-webplatform-wp'),
            TranslationAPIFacade::getInstance()->__('PoP Engine for WordPress', 'pop-engine-webplatform-wp'),
            'https://github.com/leoloso/PoP',
            POP_ENGINEWEBPLATFORMWP_POP_ENGINEWP_MIN_VERSION
        );
    }
    public function initialize_warning_2()
    {
        $this->dependencyInitializationWarning(
            TranslationAPIFacade::getInstance()->__('PoP Web Platform Engine for WordPress', 'pop-engine-webplatform-wp'),
            TranslationAPIFacade::getInstance()->__('PoP Engine Web Platform', 'pop-engine-webplatform-wp')
        );
    }
    public function install_warning_2()
    {
        $this->dependencyInstallationWarning(
            TranslationAPIFacade::getInstance()->__('PoP Web Platform Engine for WordPress', 'pop-engine-webplatform-wp'),
            TranslationAPIFacade::getInstance()->__('PoP Engine Web Platform', 'pop-engine-webplatform-wp'),
            'https://github.com/leoloso/PoP'
        );
    }
    public function version_warning_2()
    {
        $this->dependencyVersionWarning(
            TranslationAPIFacade::getInstance()->__('PoP Web Platform Engine for WordPress', 'pop-engine-webplatform-wp'),
            TranslationAPIFacade::getInstance()->__('PoP Engine Web Platform', 'pop-engine-webplatform-wp'),
            'https://github.com/leoloso/PoP',
            POP_ENGINEWEBPLATFORMWP_POP_ENGINEWP_MIN_VERSION
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
