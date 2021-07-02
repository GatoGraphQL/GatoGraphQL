<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\Hooks\Facades\HooksAPIFacade;

define('POP_CONTENTCREATIONWEBPLATFORM_POP_CONTENTCREATION_MIN_VERSION', 0.1);
define('POP_CONTENTCREATIONWEBPLATFORM_POP_TINYMCEWEBPLATFORM_MIN_VERSION', 0.1);
define('POP_CONTENTCREATIONWEBPLATFORM_POP_FORMSWEBPLATFORM_MIN_VERSION', 0.1);

class PoP_ContentCreationWebPlatform_Validation
{
    public function validate()
    {
        $success = true;
        if (!defined('POP_CONTENTCREATION_VERSION')) {
            HooksAPIFacade::getInstance()->addAction('admin_notices', array($this, 'installWarning'));
            HooksAPIFacade::getInstance()->addAction('network_admin_notices', array($this, 'installWarning'));
            $success = false;
        } elseif (!defined('POP_CONTENTCREATION_INITIALIZED')) {
            HooksAPIFacade::getInstance()->addAction('admin_notices', array($this, 'initializeWarning'));
            HooksAPIFacade::getInstance()->addAction('network_admin_notices', array($this, 'initializeWarning'));
            $success = false;
        } elseif (POP_CONTENTCREATIONWEBPLATFORM_POP_CONTENTCREATION_MIN_VERSION > POP_CONTENTCREATION_VERSION) {
            HooksAPIFacade::getInstance()->addAction('admin_notices', array($this, 'versionWarning'));
            HooksAPIFacade::getInstance()->addAction('network_admin_notices', array($this, 'versionWarning'));
        }

        if (!defined('POP_TINYMCEWEBPLATFORM_VERSION')) {
            HooksAPIFacade::getInstance()->addAction('admin_notices', array($this, 'install_warning_2'));
            HooksAPIFacade::getInstance()->addAction('network_admin_notices', array($this, 'install_warning_2'));
            $success = false;
        } elseif (!defined('POP_TINYMCEWEBPLATFORM_INITIALIZED')) {
            HooksAPIFacade::getInstance()->addAction('admin_notices', array($this, 'initialize_warning_2'));
            HooksAPIFacade::getInstance()->addAction('network_admin_notices', array($this, 'initialize_warning_2'));
            $success = false;
        } elseif (POP_CONTENTCREATIONWEBPLATFORM_POP_TINYMCEWEBPLATFORM_MIN_VERSION > POP_TINYMCEWEBPLATFORM_VERSION) {
            HooksAPIFacade::getInstance()->addAction('admin_notices', array($this, 'version_warning_2'));
            HooksAPIFacade::getInstance()->addAction('network_admin_notices', array($this, 'version_warning_2'));
        }

        if (!defined('POP_FORMSWEBPLATFORM_VERSION')) {
            HooksAPIFacade::getInstance()->addAction('admin_notices', array($this, 'install_warning_3'));
            HooksAPIFacade::getInstance()->addAction('network_admin_notices', array($this, 'install_warning_3'));
            $success = false;
        } elseif (!defined('POP_FORMSWEBPLATFORM_INITIALIZED')) {
            HooksAPIFacade::getInstance()->addAction('admin_notices', array($this, 'initialize_warning_3'));
            HooksAPIFacade::getInstance()->addAction('network_admin_notices', array($this, 'initialize_warning_3'));
            $success = false;
        } elseif (POP_CONTENTCREATIONWEBPLATFORM_POP_FORMSWEBPLATFORM_MIN_VERSION > POP_FORMSWEBPLATFORM_VERSION) {
            HooksAPIFacade::getInstance()->addAction('admin_notices', array($this, 'version_warning_3'));
            HooksAPIFacade::getInstance()->addAction('network_admin_notices', array($this, 'version_warning_3'));
        }

        return $success;
    }
    public function initializeWarning()
    {
        $this->dependencyInitializationWarning(
            TranslationAPIFacade::getInstance()->__('PoP Content Creation Web Platform', 'pop-contentcreation-webplatform'),
            TranslationAPIFacade::getInstance()->__('PoP Content Creation', 'pop-contentcreation-webplatform')
        );
    }
    public function installWarning()
    {
        $this->dependencyInstallationWarning(
            TranslationAPIFacade::getInstance()->__('PoP Content Creation Web Platform', 'pop-contentcreation-webplatform'),
            TranslationAPIFacade::getInstance()->__('PoP Content Creation', 'pop-contentcreation-webplatform'),
            'https://github.com/leoloso/PoP'
        );
    }
    public function versionWarning()
    {
        $this->dependencyVersionWarning(
            TranslationAPIFacade::getInstance()->__('PoP Content Creation Web Platform', 'pop-contentcreation-webplatform'),
            TranslationAPIFacade::getInstance()->__('PoP Content Creation', 'pop-contentcreation-webplatform'),
            'https://github.com/leoloso/PoP',
            POP_CONTENTCREATIONWEBPLATFORM_POP_CONTENTCREATION_MIN_VERSION
        );
    }
    public function initialize_warning_2()
    {
        $this->dependencyInitializationWarning(
            TranslationAPIFacade::getInstance()->__('PoP Content Creation Web Platform', 'pop-contentcreation-webplatform'),
            TranslationAPIFacade::getInstance()->__('PoP TinyMCE Web Platform', 'pop-contentcreation-webplatform')
        );
    }
    public function install_warning_2()
    {
        $this->dependencyInstallationWarning(
            TranslationAPIFacade::getInstance()->__('PoP Content Creation Web Platform', 'pop-contentcreation-webplatform'),
            TranslationAPIFacade::getInstance()->__('PoP TinyMCE Web Platform', 'pop-contentcreation-webplatform'),
            'https://github.com/leoloso/PoP'
        );
    }
    public function version_warning_2()
    {
        $this->dependencyVersionWarning(
            TranslationAPIFacade::getInstance()->__('PoP Content Creation Web Platform', 'pop-contentcreation-webplatform'),
            TranslationAPIFacade::getInstance()->__('PoP TinyMCE Web Platform', 'pop-contentcreation-webplatform'),
            'https://github.com/leoloso/PoP',
            POP_CONTENTCREATIONWEBPLATFORM_POP_TINYMCEWEBPLATFORM_MIN_VERSION
        );
    }
    public function initialize_warning_3()
    {
        $this->dependencyInitializationWarning(
            TranslationAPIFacade::getInstance()->__('PoP Content Creation Web Platform', 'pop-contentcreation-webplatform'),
            TranslationAPIFacade::getInstance()->__('PoP Forms Web Platform', 'pop-contentcreation-webplatform')
        );
    }
    public function install_warning_3()
    {
        $this->dependencyInstallationWarning(
            TranslationAPIFacade::getInstance()->__('PoP Content Creation Web Platform', 'pop-contentcreation-webplatform'),
            TranslationAPIFacade::getInstance()->__('PoP Forms Web Platform', 'pop-contentcreation-webplatform'),
            'https://github.com/leoloso/PoP'
        );
    }
    public function version_warning_3()
    {
        $this->dependencyVersionWarning(
            TranslationAPIFacade::getInstance()->__('PoP Content Creation Web Platform', 'pop-contentcreation-webplatform'),
            TranslationAPIFacade::getInstance()->__('PoP Forms Web Platform', 'pop-contentcreation-webplatform'),
            'https://github.com/leoloso/PoP',
            POP_CONTENTCREATIONWEBPLATFORM_POP_FORMSWEBPLATFORM_MIN_VERSION
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
