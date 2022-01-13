<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

define('POPGA_POP_APPLICATIONWP_MIN_VERSION', 0.1);
define('POPGA_POP_APPLICATIONWEBPLATFORM_MIN_VERSION', 0.1);

class PoP_GoogleAnalytics_Validation
{
    public function getProviderValidationClass()
    {
        return \PoP\Root\App::getHookManager()->applyFilters(
            'PoP_GoogleAnalytics_Validation:provider-validation-class',
            null
        );
    }

    public function validate()
    {
        $success = true;

        $provider_validation_class = $this->getProviderValidationClass();
        if (is_null($provider_validation_class)) {
            \PoP\Root\App::getHookManager()->addAction('admin_notices', array($this, 'providerinstall_warning'));
            \PoP\Root\App::getHookManager()->addAction('network_admin_notices', array($this, 'providerinstall_warning'));
            $success = false;
        } elseif (!(new $provider_validation_class())->validate()) {
            \PoP\Root\App::getHookManager()->addAction('admin_notices', array($this, 'providerinitialize_warning'));
            \PoP\Root\App::getHookManager()->addAction('network_admin_notices', array($this, 'providerinitialize_warning'));
            $success = false;
        }

        if (!defined('POP_APPLICATIONWEBPLATFORM_VERSION')) {
            \PoP\Root\App::getHookManager()->addAction('admin_notices', array($this, 'installWarning'));
            \PoP\Root\App::getHookManager()->addAction('network_admin_notices', array($this, 'installWarning'));
            $success = false;
        } elseif (!defined('POP_APPLICATIONWEBPLATFORM_INITIALIZED')) {
            \PoP\Root\App::getHookManager()->addAction('admin_notices', array($this, 'initializeWarning'));
            \PoP\Root\App::getHookManager()->addAction('network_admin_notices', array($this, 'initializeWarning'));
            $success = false;
        } elseif (POPGA_POP_APPLICATIONWEBPLATFORM_MIN_VERSION > POP_APPLICATIONWEBPLATFORM_VERSION) {
            \PoP\Root\App::getHookManager()->addAction('admin_notices', array($this, 'versionWarning'));
            \PoP\Root\App::getHookManager()->addAction('network_admin_notices', array($this, 'versionWarning'));
        }

        return $success;
    }
    public function providerinstall_warning()
    {
        $this->providerinstall_warning_notice(
            TranslationAPIFacade::getInstance()->__('PoP Google Analytics', 'pop-googleanalytics')
        );
    }
    public function providerinitialize_warning()
    {
        $this->providerinitialize_warning_notice(
            TranslationAPIFacade::getInstance()->__('PoP Google Analytics', 'pop-googleanalytics')
        );
    }
    public function initializeWarning()
    {
        $this->dependencyInitializationWarning(
            TranslationAPIFacade::getInstance()->__('PoP Google Analytics', 'pop-googleanalytics'),
            TranslationAPIFacade::getInstance()->__('PoP Application Web Platform', 'pop-googleanalytics')
        );
    }
    public function installWarning()
    {
        $this->dependencyInstallationWarning(
            TranslationAPIFacade::getInstance()->__('PoP Google Analytics', 'pop-googleanalytics'),
            TranslationAPIFacade::getInstance()->__('PoP Application Web Platform', 'pop-googleanalytics'),
            'https://github.com/leoloso/PoP'
        );
    }
    public function versionWarning()
    {
        $this->dependencyVersionWarning(
            TranslationAPIFacade::getInstance()->__('PoP Google Analytics', 'pop-googleanalytics'),
            TranslationAPIFacade::getInstance()->__('PoP Application Web Platform', 'pop-googleanalytics'),
            'https://github.com/leoloso/PoP',
            POPGA_POP_APPLICATIONWEBPLATFORM_MIN_VERSION
        );
    }
    public function pluginWarning()
    {
        $this->dependencyInstallationWarning(
            TranslationAPIFacade::getInstance()->__('PoP Google Analytics', 'pop-googleanalytics'),
            TranslationAPIFacade::getInstance()->__('Google Analytics Dashboard for WP', 'pop-googleanalytics'),
            'https://wordpress.org/plugins/google-analytics-dashboard-for-wp/'
        );
    }
    protected function providerinstall_warning_notice($plugin)
    {
        $this->adminNotice(
            sprintf(
                TranslationAPIFacade::getInstance()->__('Error: %s', 'pop-engine-webplatform'),
                sprintf(
                    TranslationAPIFacade::getInstance()->__('There is no provider (underlying implementation) for <strong>%s</strong>.', 'pop-engine-webplatform'),
                    $plugin
                )
            )
        );
    }
    protected function providerinitialize_warning_notice($plugin)
    {
        $this->adminNotice(
            sprintf(
                TranslationAPIFacade::getInstance()->__('Error: %s', 'pop-engine-webplatform'),
                sprintf(
                    TranslationAPIFacade::getInstance()->__('<strong>%s</strong> has not been loaded, because its provider (underlying implementation) was not initialized properly.', 'pop-engine-webplatform'),
                    $plugin
                )
            )
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
