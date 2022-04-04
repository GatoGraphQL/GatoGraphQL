<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

define('POP_ADDCOAUTHORS_POP_COAUTHORS_MIN_VERSION', 0.1);
define('POP_ADDCOAUTHORS_POP_USERPLATFORM_MIN_VERSION', 0.1);

class PoP_AddCoauthors_Validation
{
    public function getProviderValidationClass()
    {
        return \PoP\Root\App::applyFilters(
            'PoP_AddCoauthors_Validation:provider-validation-class',
            null
        );
    }

    public function validate()
    {
        $success = true;
        
        $provider_validation_class = $this->getProviderValidationClass();
        if (is_null($provider_validation_class)) {
            \PoP\Root\App::addAction('admin_notices', $this->providerinstall_warning(...));
            \PoP\Root\App::addAction('network_admin_notices', $this->providerinstall_warning(...));
            $success = false;
        } elseif (!(new $provider_validation_class())->validate()) {
            \PoP\Root\App::addAction('admin_notices', $this->providerinitialize_warning(...));
            \PoP\Root\App::addAction('network_admin_notices', $this->providerinitialize_warning(...));
            $success = false;
        }

        if (!defined('POP_COAUTHORS_VERSION')) {
            \PoP\Root\App::addAction('admin_notices', $this->installWarning(...));
            \PoP\Root\App::addAction('network_admin_notices', $this->installWarning(...));
            $success = false;
        } elseif (!defined('POP_COAUTHORS_INITIALIZED')) {
            \PoP\Root\App::addAction('admin_notices', $this->initializeWarning(...));
            \PoP\Root\App::addAction('network_admin_notices', $this->initializeWarning(...));
            $success = false;
        } elseif (POP_ADDCOAUTHORS_POP_COAUTHORS_MIN_VERSION > POP_COAUTHORS_VERSION) {
            \PoP\Root\App::addAction('admin_notices', $this->versionWarning(...));
            \PoP\Root\App::addAction('network_admin_notices', $this->versionWarning(...));
        }

        if (!defined('POP_USERPLATFORM_VERSION')) {
            \PoP\Root\App::addAction('admin_notices', $this->install_warning_2(...));
            \PoP\Root\App::addAction('network_admin_notices', $this->install_warning_2(...));
            $success = false;
        } elseif (!defined('POP_USERPLATFORM_INITIALIZED')) {
            \PoP\Root\App::addAction('admin_notices', $this->initialize_warning_2(...));
            \PoP\Root\App::addAction('network_admin_notices', $this->initialize_warning_2(...));
            $success = false;
        } elseif (POP_ADDCOAUTHORS_POP_USERPLATFORM_MIN_VERSION > POP_USERPLATFORM_VERSION) {
            \PoP\Root\App::addAction('admin_notices', $this->version_warning_2(...));
            \PoP\Root\App::addAction('network_admin_notices', $this->version_warning_2(...));
        }

        return $success;
    }
    public function providerinstall_warning()
    {
        $this->providerinstall_warning_notice(
            TranslationAPIFacade::getInstance()->__('PoP Add Coauthors', 'pop-addcoauthors')
        );
    }
    public function providerinitialize_warning()
    {
        $this->providerinitialize_warning_notice(
            TranslationAPIFacade::getInstance()->__('PoP Add Coauthors', 'pop-addcoauthors')
        );
    }
    public function initializeWarning()
    {
        $this->dependencyInitializationWarning(
            TranslationAPIFacade::getInstance()->__('PoP Add Coauthors', 'pop-addcoauthors'),
            TranslationAPIFacade::getInstance()->__('PoP Coauthors', 'pop-addcoauthors')
        );
    }
    public function installWarning()
    {
        $this->dependencyInstallationWarning(
            TranslationAPIFacade::getInstance()->__('PoP Add Coauthors', 'pop-addcoauthors'),
            TranslationAPIFacade::getInstance()->__('PoP Coauthors', 'pop-addcoauthors'),
            'https://github.com/leoloso/PoP'
        );
    }
    public function versionWarning()
    {
        $this->dependencyVersionWarning(
            TranslationAPIFacade::getInstance()->__('PoP Add Coauthors', 'pop-addcoauthors'),
            TranslationAPIFacade::getInstance()->__('PoP Coauthors', 'pop-addcoauthors'),
            'https://github.com/leoloso/PoP',
            POP_ADDCOAUTHORS_POP_COAUTHORS_MIN_VERSION
        );
    }
    public function initialize_warning_2()
    {
        $this->dependencyInitializationWarning(
            TranslationAPIFacade::getInstance()->__('PoP Add Coauthors', 'pop-addcoauthors'),
            TranslationAPIFacade::getInstance()->__('PoP User Platform', 'pop-addcoauthors')
        );
    }
    public function install_warning_2()
    {
        $this->dependencyInstallationWarning(
            TranslationAPIFacade::getInstance()->__('PoP Add Coauthors', 'pop-addcoauthors'),
            TranslationAPIFacade::getInstance()->__('PoP User Platform', 'pop-addcoauthors'),
            'https://github.com/leoloso/PoP'
        );
    }
    public function version_warning_2()
    {
        $this->dependencyVersionWarning(
            TranslationAPIFacade::getInstance()->__('PoP Add Coauthors', 'pop-addcoauthors'),
            TranslationAPIFacade::getInstance()->__('PoP User Platform', 'pop-addcoauthors'),
            'https://github.com/leoloso/PoP',
            POP_ADDCOAUTHORS_POP_USERPLATFORM_MIN_VERSION
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
