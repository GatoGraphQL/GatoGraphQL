<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

define('POP_LOCATIONPOSTS_POP_LOCATIONS_MIN_VERSION', 0.1);

class PoP_LocationPosts_Validation
{
    public function validate()
    {
        $success = true;
        if (!defined('POP_LOCATIONS_VERSION')) {
            \PoP\Root\App::getHookManager()->addAction('admin_notices', array($this, 'installWarning'));
            \PoP\Root\App::getHookManager()->addAction('network_admin_notices', array($this, 'installWarning'));
            $success = false;
        } elseif (!defined('POP_LOCATIONS_INITIALIZED')) {
            \PoP\Root\App::getHookManager()->addAction('admin_notices', array($this, 'initializeWarning'));
            \PoP\Root\App::getHookManager()->addAction('network_admin_notices', array($this, 'initializeWarning'));
            $success = false;
        } elseif (POP_LOCATIONPOSTS_POP_LOCATIONS_MIN_VERSION > POP_LOCATIONS_VERSION) {
            \PoP\Root\App::getHookManager()->addAction('admin_notices', array($this, 'versionWarning'));
            \PoP\Root\App::getHookManager()->addAction('network_admin_notices', array($this, 'versionWarning'));
        }

        return $success;
    }
    public function initializeWarning()
    {
        $this->dependencyInitializationWarning(
            TranslationAPIFacade::getInstance()->__('PoP Location Posts', 'pop-locationposts'),
            TranslationAPIFacade::getInstance()->__('PoP Locations', 'pop-locationposts')
        );
    }
    public function installWarning()
    {
        $this->dependencyInstallationWarning(
            TranslationAPIFacade::getInstance()->__('PoP Location Posts', 'pop-locationposts'),
            TranslationAPIFacade::getInstance()->__('PoP Locations', 'pop-locationposts'),
            'https://github.com/leoloso/PoP'
        );
    }
    public function versionWarning()
    {
        $this->dependencyVersionWarning(
            TranslationAPIFacade::getInstance()->__('PoP Location Posts', 'pop-locationposts'),
            TranslationAPIFacade::getInstance()->__('PoP Locations', 'pop-locationposts'),
            'https://github.com/leoloso/PoP',
            POP_LOCATIONPOSTS_POP_LOCATIONS_MIN_VERSION
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
