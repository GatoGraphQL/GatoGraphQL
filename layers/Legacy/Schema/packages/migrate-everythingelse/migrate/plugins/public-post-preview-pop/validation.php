<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\Hooks\Facades\HooksAPIFacade;

define('PPPPOP_POP_APPLICATIONWP_MIN_VERSION', 0.1);

class PPP_PoP_Validation
{
    public function validate()
    {
        $success = true;

        // Validate plug-in
        if (!class_exists('DS_Public_Post_Preview')) {
            HooksAPIFacade::getInstance()->addAction('admin_notices', array($this,'pluginWarning'));
            HooksAPIFacade::getInstance()->addAction('network_admin_notices', array($this,'pluginWarning'));
            $success = false;
        }

        return $success;
    }
    public function pluginWarning()
    {
        $this->dependencyInstallationWarning(
            TranslationAPIFacade::getInstance()->__('Public Post Preview for PoP', 'ppp-pop'),
            TranslationAPIFacade::getInstance()->__('Public Post Preview', 'ppp-pop'),
            'https://wordpress.org/plugins/public-post-preview/'
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
