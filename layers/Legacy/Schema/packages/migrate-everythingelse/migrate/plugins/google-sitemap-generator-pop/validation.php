<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

define('POP_GSG_POP_CMSWP_MIN_VERSION', 0.1);

class PoP_GSG_Validation
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
        } elseif (POP_GSG_POP_CMSWP_MIN_VERSION > POP_ENGINEWP_VERSION) {
            \PoP\Root\App::getHookManager()->addAction('admin_notices', array($this, 'versionWarning'));
            \PoP\Root\App::getHookManager()->addAction('network_admin_notices', array($this, 'versionWarning'));
        }

        // Validate plug-in
        if (!class_exists('GoogleSitemapGeneratorLoader')) {
            \PoP\Root\App::getHookManager()->addAction('admin_notices', array($this,'pluginWarning'));
            \PoP\Root\App::getHookManager()->addAction('network_admin_notices', array($this,'pluginWarning'));
            $success = false;
        }

        return $success;
    }
    public function initializeWarning()
    {
        $this->dependencyInitializationWarning(
            TranslationAPIFacade::getInstance()->__('Google XML Sitemaps for PoP', 'pop-gsg'),
            TranslationAPIFacade::getInstance()->__('PoP Engine for WordPress', 'pop-gsg')
        );
    }
    public function installWarning()
    {
        $this->dependencyInstallationWarning(
            TranslationAPIFacade::getInstance()->__('Google XML Sitemaps for PoP', 'pop-gsg'),
            TranslationAPIFacade::getInstance()->__('PoP Engine for WordPress', 'pop-gsg'),
            'https://github.com/leoloso/PoP'
        );
    }
    public function versionWarning()
    {
        $this->dependencyVersionWarning(
            TranslationAPIFacade::getInstance()->__('Google XML Sitemaps for PoP', 'pop-gsg'),
            TranslationAPIFacade::getInstance()->__('PoP Engine for WordPress', 'pop-gsg'),
            'https://github.com/leoloso/PoP',
            POP_GSG_POP_CMSWP_MIN_VERSION
        );
    }
    public function pluginWarning()
    {
        $this->dependencyInstallationWarning(
            TranslationAPIFacade::getInstance()->__('Google XML Sitemaps for PoP', 'pop-gsg'),
            TranslationAPIFacade::getInstance()->__('Google XML Sitemaps', 'pop-gsg'),
            'https://wordpress.org/plugins/google-sitemap-generator/'
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
