<?php
namespace PoP\TaxonomyQuery;

define('POP_TAXONOMYQUERY_POP_ENGINE_MIN_VERSION', 0.1);

class Validation
{
    public function getProviderValidationClass()
    {
        return \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters(
            'PoP_TaxonomyQuery_Validation:provider-validation-class',
            null
        );
    }

    public function validate()
    {
        $success = true;

        $provider_validation_class = $this->getProviderValidationClass();
        if (is_null($provider_validation_class)) {
            \PoP\CMS\HooksAPI_Factory::getInstance()->addAction('admin_notices', array($this, 'providerinstall_warning'));
            \PoP\CMS\HooksAPI_Factory::getInstance()->addAction('network_admin_notices', array($this, 'providerinstall_warning'));
            $success = false;
        } elseif (!(new $provider_validation_class())->validate()) {
            \PoP\CMS\HooksAPI_Factory::getInstance()->addAction('admin_notices', array($this, 'providerinitialize_warning'));
            \PoP\CMS\HooksAPI_Factory::getInstance()->addAction('network_admin_notices', array($this, 'providerinitialize_warning'));
            $success = false;
        }
        
        if (!defined('POP_ENGINE_VERSION')) {
            \PoP\CMS\HooksAPI_Factory::getInstance()->addAction('admin_notices', array($this, 'installWarning'));
            \PoP\CMS\HooksAPI_Factory::getInstance()->addAction('network_admin_notices', array($this, 'installWarning'));
            $success = false;
        } elseif (!defined('POP_ENGINE_INITIALIZED')) {
            \PoP\CMS\HooksAPI_Factory::getInstance()->addAction('admin_notices', array($this, 'initializeWarning'));
            \PoP\CMS\HooksAPI_Factory::getInstance()->addAction('network_admin_notices', array($this, 'initializeWarning'));
            $success = false;
        } elseif (POP_TAXONOMYQUERY_POP_ENGINE_MIN_VERSION > POP_ENGINE_VERSION) {
            \PoP\CMS\HooksAPI_Factory::getInstance()->addAction('admin_notices', array($this, 'versionWarning'));
            \PoP\CMS\HooksAPI_Factory::getInstance()->addAction('network_admin_notices', array($this, 'versionWarning'));
        }

        return $success;
    }
    public function providerinstall_warning()
    {
        $this->providerinstall_warning_notice(
            __('PoP Taxonomy Query', 'pop-taxonomyquery')
        );
    }
    public function providerinitialize_warning()
    {
        $this->providerinitialize_warning_notice(
            __('PoP Taxonomy Query', 'pop-taxonomyquery')
        );
    }
    public function initializeWarning()
    {
        $this->dependencyInitializationWarning(
            __('PoP Taxonomy Query', 'pop-taxonomyquery'),
            __('PoP Engine', 'pop-taxonomyquery')
        );
    }
    public function installWarning()
    {
        $this->dependencyInstallationWarning(
            __('PoP Taxonomy Query', 'pop-taxonomyquery'),
            __('PoP Engine', 'pop-taxonomyquery'),
            'https://github.com/leoloso/PoP'
        );
    }
    public function versionWarning()
    {
        $this->dependencyVersionWarning(
            __('PoP Taxonomy Query', 'pop-taxonomyquery'),
            __('PoP Engine', 'pop-taxonomyquery'),
            'https://github.com/leoloso/PoP',
            POP_TAXONOMYQUERY_POP_ENGINE_MIN_VERSION
        );
    }
    protected function providerinstall_warning_notice($plugin)
    {
        $this->adminNotice(
            sprintf(
                __('Error: %s', 'pop-engine-frontend'),
                sprintf(
                    __('There is no provider (underlying implementation) for <strong>%s</strong>.', 'pop-engine-frontend'),
                    $plugin
                )
            )
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
