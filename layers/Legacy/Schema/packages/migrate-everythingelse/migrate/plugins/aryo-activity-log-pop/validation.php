<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

define('POP_AALPOP_POP_CMSWP_MIN_VERSION', 0.1);

class AAL_PoP_Validation
{
    public function validate()
    {
        $success = true;

        // Validate plug-in
        if (!class_exists('AAL_Main')) {
            \PoP\Root\App::getHookManager()->addAction('admin_notices', array($this,'pluginWarning'));
            \PoP\Root\App::getHookManager()->addAction('network_admin_notices', array($this,'pluginWarning'));
            $success = false;
        }

        return $success;
    }
    public function pluginWarning()
    {
        $this->dependencyInstallationWarning(
            TranslationAPIFacade::getInstance()->__('Activity Log for PoP', 'aal-pop'),
            TranslationAPIFacade::getInstance()->__('Activity Log', 'aal-pop'),
            'https://wordpress.org/plugins/aryo-activity-log/'
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
