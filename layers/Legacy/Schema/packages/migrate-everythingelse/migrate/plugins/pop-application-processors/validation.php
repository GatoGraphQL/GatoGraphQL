<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

define('POP_APPLICATIONPROCESSORS_POP_MASTERCOLLECTIONPROCESSORS_MIN_VERSION', 0.1);
define('POP_APPLICATIONPROCESSORS_POP_APPLICATION_MIN_VERSION', 0.1);
define('POP_APPLICATIONPROCESSORS_POP_BOOTSTRAPPROCESSORS_MIN_VERSION', 0.1);
// define('POP_APPLICATIONPROCESSORS_POP_FORMSPROCESSORS_MIN_VERSION', 0.1);

class PoP_ApplicationProcessors_Validation
{
    public function validate()
    {
        $success = true;
        if (!defined('POP_MASTERCOLLECTIONPROCESSORS_VERSION')) {
            \PoP\Root\App::addAction('admin_notices', array($this,'installWarning'));
            \PoP\Root\App::addAction('network_admin_notices', array($this,'installWarning'));
            $success = false;
        } elseif (!defined('POP_MASTERCOLLECTIONPROCESSORS_INITIALIZED')) {
            \PoP\Root\App::addAction('admin_notices', array($this, 'initializeWarning'));
            \PoP\Root\App::addAction('network_admin_notices', array($this, 'initializeWarning'));
            $success = false;
        } elseif (POP_APPLICATIONPROCESSORS_POP_MASTERCOLLECTIONPROCESSORS_MIN_VERSION > POP_MASTERCOLLECTIONPROCESSORS_VERSION) {
            \PoP\Root\App::addAction('admin_notices', array($this,'versionWarning'));
            \PoP\Root\App::addAction('network_admin_notices', array($this,'versionWarning'));
        }

        if (!defined('POP_APPLICATION_VERSION')) {
            \PoP\Root\App::addAction('admin_notices', array($this,'install_warning_2'));
            \PoP\Root\App::addAction('network_admin_notices', array($this,'install_warning_2'));
            $success = false;
        } elseif (!defined('POP_APPLICATION_INITIALIZED')) {
            \PoP\Root\App::addAction('admin_notices', array($this, 'initialize_warning_2'));
            \PoP\Root\App::addAction('network_admin_notices', array($this, 'initialize_warning_2'));
            $success = false;
        } elseif (POP_APPLICATIONPROCESSORS_POP_APPLICATION_MIN_VERSION > POP_APPLICATION_VERSION) {
            \PoP\Root\App::addAction('admin_notices', array($this,'version_warning_2'));
            \PoP\Root\App::addAction('network_admin_notices', array($this,'version_warning_2'));
        }

        if (!defined('POP_BOOTSTRAPPROCESSORS_VERSION')) {
            \PoP\Root\App::addAction('admin_notices', array($this,'install_warning_3'));
            \PoP\Root\App::addAction('network_admin_notices', array($this,'install_warning_3'));
            $success = false;
        } elseif (!defined('POP_BOOTSTRAPPROCESSORS_INITIALIZED')) {
            \PoP\Root\App::addAction('admin_notices', array($this, 'initialize_warning_3'));
            \PoP\Root\App::addAction('network_admin_notices', array($this, 'initialize_warning_3'));
            $success = false;
        } elseif (POP_APPLICATIONPROCESSORS_POP_BOOTSTRAPPROCESSORS_MIN_VERSION > POP_BOOTSTRAPPROCESSORS_VERSION) {
            \PoP\Root\App::addAction('admin_notices', array($this,'version_warning_3'));
            \PoP\Root\App::addAction('network_admin_notices', array($this,'version_warning_3'));
        }

        // if (!defined('POP_FORMSPROCESSORS_VERSION')) {

        //     \PoP\Root\App::addAction('admin_notices',array($this,'install_warning_3'));
        //     \PoP\Root\App::addAction('network_admin_notices',array($this,'install_warning_3'));
        //     $success = false;
        // }
        // elseif(!defined('POP_FORMSPROCESSORS_INITIALIZED')) {

        //     \PoP\Root\App::addAction('admin_notices', array($this, 'initialize_warning_3'));
        //     \PoP\Root\App::addAction('network_admin_notices', array($this, 'initialize_warning_3'));
        //     $success = false;
        // }
        // elseif (POP_APPLICATIONPROCESSORS_POP_FORMSPROCESSORS_MIN_VERSION > POP_FORMSPROCESSORS_VERSION) {
            
        //     \PoP\Root\App::addAction('admin_notices',array($this,'version_warning_3'));
        //     \PoP\Root\App::addAction('network_admin_notices',array($this,'version_warning_3'));
        // }

        return $success;
    }
    public function initializeWarning()
    {
        $this->dependencyInitializationWarning(
            TranslationAPIFacade::getInstance()->__('PoP Application Processors', 'pop-application-processors'),
            TranslationAPIFacade::getInstance()->__('PoP Master Collection Processors', 'pop-application-processors')
        );
    }
    public function installWarning()
    {
        $this->dependencyInstallationWarning(
            TranslationAPIFacade::getInstance()->__('PoP Application Processors', 'pop-application-processors'),
            TranslationAPIFacade::getInstance()->__('PoP Master Collection Processors', 'pop-application-processors'),
            'https://github.com/leoloso/PoP'
        );
    }
    public function versionWarning()
    {
        $this->dependencyVersionWarning(
            TranslationAPIFacade::getInstance()->__('PoP Application Processors', 'pop-application-processors'),
            TranslationAPIFacade::getInstance()->__('PoP Master Collection Processors', 'pop-application-processors'),
            'https://github.com/leoloso/PoP',
            POP_APPLICATIONPROCESSORS_POP_MASTERCOLLECTIONPROCESSORS_MIN_VERSION
        );
    }
    public function initialize_warning_2()
    {
        $this->dependencyInitializationWarning(
            TranslationAPIFacade::getInstance()->__('PoP Application Processors', 'pop-application-processors'),
            TranslationAPIFacade::getInstance()->__('PoP Application', 'pop-application-processors')
        );
    }
    public function install_warning_2()
    {
        $this->dependencyInstallationWarning(
            TranslationAPIFacade::getInstance()->__('PoP Application Processors', 'pop-application-processors'),
            TranslationAPIFacade::getInstance()->__('PoP Application', 'pop-application-processors'),
            'https://github.com/leoloso/PoP'
        );
    }
    public function version_warning_2()
    {
        $this->dependencyVersionWarning(
            TranslationAPIFacade::getInstance()->__('PoP Application Processors', 'pop-application-processors'),
            TranslationAPIFacade::getInstance()->__('PoP Application', 'pop-application-processors'),
            'https://github.com/leoloso/PoP',
            POP_APPLICATIONPROCESSORS_POP_APPLICATION_MIN_VERSION
        );
    }
    public function initialize_warning_3()
    {
        $this->dependencyInitializationWarning(
            TranslationAPIFacade::getInstance()->__('PoP Application Processors', 'pop-application-processors'),
            TranslationAPIFacade::getInstance()->__('PoP Bootstrap Processors', 'pop-application-processors')
        );
    }
    public function install_warning_3()
    {
        $this->dependencyInstallationWarning(
            TranslationAPIFacade::getInstance()->__('PoP Application Processors', 'pop-application-processors'),
            TranslationAPIFacade::getInstance()->__('PoP Bootstrap Processors', 'pop-application-processors'),
            'https://github.com/leoloso/PoP'
        );
    }
    public function version_warning_3()
    {
        $this->dependencyVersionWarning(
            TranslationAPIFacade::getInstance()->__('PoP Application Processors', 'pop-application-processors'),
            TranslationAPIFacade::getInstance()->__('PoP Bootstrap Processors', 'pop-application-processors'),
            'https://github.com/leoloso/PoP',
            POP_APPLICATIONPROCESSORS_POP_BOOTSTRAPPROCESSORS_MIN_VERSION
        );
    }
    // function initialize_warning_3() {
        
    //     $this->dependencyInitializationWarning(
    //         TranslationAPIFacade::getInstance()->__('PoP Application Processors', 'pop-application-processors'),
    //         TranslationAPIFacade::getInstance()->__('PoP Forms Processors', 'pop-application-processors')
    //     );
    // }
    // function install_warning_3() {
        
    //     $this->dependencyInstallationWarning(
    //         TranslationAPIFacade::getInstance()->__('PoP Application Processors', 'pop-application-processors'),
    //         TranslationAPIFacade::getInstance()->__('PoP Forms Processors', 'pop-application-processors'),
    //         'https://github.com/leoloso/PoP'
    //     );
    // }
    // function version_warning_3() {
        
    //     $this->dependencyVersionWarning(
    //         TranslationAPIFacade::getInstance()->__('PoP Application Processors', 'pop-application-processors'),
    //         TranslationAPIFacade::getInstance()->__('PoP Forms Processors', 'pop-application-processors'),
    //         'https://github.com/leoloso/PoP',
    //         POP_APPLICATIONPROCESSORS_POP_FORMSPROCESSORS_MIN_VERSION
    //     );
    // }
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
