<?php
namespace PoP\CMS\WP\Frontend;

define('POP_CMSFRONTENDWP_POP_CMSFRONTEND_MIN_VERSION', 0.1);

class Validation
{
    public function validate()
    {
        $success = true;
        if (!defined('POP_CMSFRONTEND_VERSION')) {
            \PoP\CMS\HooksAPI_Factory::getInstance()->addAction('admin_notices', array($this, 'installWarning'));
            \PoP\CMS\HooksAPI_Factory::getInstance()->addAction('network_admin_notices', array($this, 'installWarning'));
            $success = false;
        } elseif (!defined('POP_CMSFRONTEND_INITIALIZED')) {
            \PoP\CMS\HooksAPI_Factory::getInstance()->addAction('admin_notices', array($this, 'initializeWarning'));
            \PoP\CMS\HooksAPI_Factory::getInstance()->addAction('network_admin_notices', array($this, 'initializeWarning'));
            $success = false;
        } elseif (POP_CMSFRONTENDWP_POP_CMSFRONTEND_MIN_VERSION > POP_CMSFRONTEND_VERSION) {
            \PoP\CMS\HooksAPI_Factory::getInstance()->addAction('admin_notices', array($this, 'versionWarning'));
            \PoP\CMS\HooksAPI_Factory::getInstance()->addAction('network_admin_notices', array($this, 'versionWarning'));
        }

        return $success;
    }
    public function initializeWarning()
    {
        $this->dependencyInitializationWarning(
            __('PoP WordPress CMS', 'pop-cmsfrontend-wp'),
            __('PoP CMS Frontend', 'pop-cmsfrontend-wp')
        );
    }
    public function installWarning()
    {
        $this->dependencyInstallationWarning(
            __('PoP WordPress CMS', 'pop-cmsfrontend-wp'),
            __('PoP CMS Frontend', 'pop-cmsfrontend-wp'),
            'https://github.com/leoloso/PoP'
        );
    }
    public function versionWarning()
    {
        $this->dependencyVersionWarning(
            __('PoP WordPress CMS', 'pop-cmsfrontend-wp'),
            __('PoP CMS Frontend', 'pop-cmsfrontend-wp'),
            'https://github.com/leoloso/PoP',
            POP_CMSFRONTENDWP_POP_CMSFRONTEND_MIN_VERSION
        );
    }
    protected function dependencyInstallationWarning($plugin, $dependency, $dependency_url)
    {
        $this->adminNotice(
            sprintf(
                __('Error: %s', 'pop-engine-frontend'),
                sprintf(
                    __('<strong>%s</strong> is not installed/activated. Without it, <strong>%s</strong> will not work. Please install this plugin from your plugin installer or download it <a href="%s" target="_blank">from here</a>.', 'pop-engine-frontend'),
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
                __('Error: %s', 'pop-engine-frontend'),
                sprintf(
                    __('<strong>%s</strong> is not initialized properly. As a consequence, <strong>%s</strong> has not been loaded.', 'pop-engine-frontend'),
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
                __('Error: %s', 'pop-engine-frontend'),
                sprintf(
                    __('<strong>%s</strong> requires version %s or bigger of <strong>%s</strong>. Please update this plugin from your plugin installer or download it <a href="%s" target="_blank">from here</a>.', 'pop-engine-frontend'),
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
        <?php _e('Only admins see this message.', 'pop-engine-frontend'); ?>
                </em>
            </p>
        </div>
        <?php
    }
}
