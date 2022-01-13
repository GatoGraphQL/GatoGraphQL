<?php
namespace PoP\Theme;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

define('POP_THEMEWP_POP_ENGINEWP_MIN_VERSION', 0.1);

class Validation
{
    public function validate()
    {
        $success = true;
        if (!defined('POP_ENGINEWP_VERSION')) {
            \PoP\Root\App::addAction('admin_notices', array($this, 'install_warning'));
            \PoP\Root\App::addAction('network_admin_notices', array($this, 'install_warning'));
            $success = false;
        } elseif (!defined('POP_ENGINEWP_INITIALIZED')) {
            \PoP\Root\App::addAction('admin_notices', array($this, 'initialize_warning'));
            \PoP\Root\App::addAction('network_admin_notices', array($this, 'initialize_warning'));
            $success = false;
        } elseif (POP_THEMEWP_POP_ENGINEWP_MIN_VERSION > POP_ENGINEWP_VERSION) {
            \PoP\Root\App::addAction('admin_notices', array($this, 'version_warning'));
            \PoP\Root\App::addAction('network_admin_notices', array($this, 'version_warning'));
        }

        if (!defined('POP_THEME_VERSION')) {
            \PoP\Root\App::addAction('admin_notices', array($this, 'install_warning_2'));
            \PoP\Root\App::addAction('network_admin_notices', array($this, 'install_warning_2'));
            $success = false;
        }

        return $success;
    }
    public function initialize_warning()
    {
        $this->dependencyInitializationWarning(
            TranslationAPIFacade::getInstance()->__('PoP Theme for WordPress', 'pop-theme-wp'),
            TranslationAPIFacade::getInstance()->__('PoP Engine for WordPress', 'pop-theme-wp')
        );
    }
    public function install_warning()
    {
        $this->dependencyInstallationWarning(
            TranslationAPIFacade::getInstance()->__('PoP Theme for WordPress', 'pop-theme-wp'),
            TranslationAPIFacade::getInstance()->__('PoP Engine for WordPress', 'pop-theme-wp'),
            'https://github.com/leoloso/PoP'
        );
    }
    public function version_warning()
    {
        $this->dependencyVersionWarning(
            TranslationAPIFacade::getInstance()->__('PoP Theme for WordPress', 'pop-theme-wp'),
            TranslationAPIFacade::getInstance()->__('PoP Engine for WordPress', 'pop-theme-wp'),
            'https://github.com/leoloso/PoP',
            POP_THEMEWP_POP_ENGINEWP_MIN_VERSION
        );
    }
    public function install_warning_2()
    {
        $this->dependencyInstallationWarning(
            TranslationAPIFacade::getInstance()->__('PoP Theme for WordPress', 'pop-theme-wp'),
            TranslationAPIFacade::getInstance()->__('PoP Theme', 'pop-theme-wp'),
            'https://github.com/leoloso/PoP'
        );
    }
    protected function dependencyInstallationWarning($plugin, $dependency, $dependency_url)
    {
        $this->adminNotice(
            sprintf(
                TranslationAPIFacade::getInstance()->__('Error: %s', 'pop-theme'),
                sprintf(
                    TranslationAPIFacade::getInstance()->__('<strong>%s</strong> is not installed/activated. Without it, <strong>%s</strong> will not work. Please install this plugin from your plugin installer or download it <a href="%s" target="_blank">from here</a>.', 'pop-theme'),
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
                TranslationAPIFacade::getInstance()->__('Error: %s', 'pop-theme'),
                sprintf(
                    TranslationAPIFacade::getInstance()->__('<strong>%s</strong> is not initialized properly. As a consequence, <strong>%s</strong> has not been loaded.', 'pop-theme'),
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
                TranslationAPIFacade::getInstance()->__('Error: %s', 'pop-theme'),
                sprintf(
                    TranslationAPIFacade::getInstance()->__('<strong>%s</strong> requires version %s or bigger of <strong>%s</strong>. Please update this plugin from your plugin installer or download it <a href="%s" target="_blank">from here</a>.', 'pop-theme'),
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
        <?php _e('Only admins see this message.', 'pop-theme'); ?>
                </em>
            </p>
        </div>
        <?php
    }
}
