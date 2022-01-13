<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class UserAvatarPoPForkPoP_Validation
{
    public function validate()
    {
        $success = true;

        // Validate plug-in
        if (!function_exists('user_avatar_init')) {
            HooksAPIFacade::getInstance()->addAction('admin_notices', array($this,'pluginWarning'));
            HooksAPIFacade::getInstance()->addAction('network_admin_notices', array($this,'pluginWarning'));
            $success = false;
        }

        return $success;
    }
    public function pluginWarning()
    {
        $this->dependencyInstallationWarning(
            TranslationAPIFacade::getInstance()->__('User Avatar for PoP', 'user-avatar-popfork-pop'),
            TranslationAPIFacade::getInstance()->__('User Avatar (forked for PoP)', 'user-avatar-popfork-pop'),
            'https://github.com/leoloso/PoP'
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
