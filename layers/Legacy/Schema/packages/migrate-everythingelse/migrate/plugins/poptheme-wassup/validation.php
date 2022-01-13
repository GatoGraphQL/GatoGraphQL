<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

define('PTW_POP_APPLICATIONPROCESSORS_MIN_VERSION', 0.1);
// define('PTW_POP_GENERICFORMS_MIN_VERSION', 0.1);
define('PTW_POP_BOOTSTRAPPROCESSORS_MIN_VERSION', 0.1);
define('PTW_POP_APPLICATIONPROCESSORS_MIN_VERSION', 0.1);
define('PTW_POP_SPAPROCESSORS_MIN_VERSION', 0.1);
define('PTW_POP_THEME_MIN_VERSION', 0.1);

class PoPTheme_Wassup_Validation
{
    public function validate()
    {
        $success = true;
        if (!defined('POP_APPLICATIONPROCESSORS_VERSION')) {
            \PoP\Root\App::getHookManager()->addAction('admin_notices', array($this,'installWarning'));
            \PoP\Root\App::getHookManager()->addAction('network_admin_notices', array($this,'installWarning'));
            $success = false;
        } elseif (!defined('POP_APPLICATIONPROCESSORS_INITIALIZED')) {
            \PoP\Root\App::getHookManager()->addAction('admin_notices', array($this, 'initializeWarning'));
            \PoP\Root\App::getHookManager()->addAction('network_admin_notices', array($this, 'initializeWarning'));
            $success = false;
        } elseif (PTW_POP_APPLICATIONPROCESSORS_MIN_VERSION > POP_APPLICATIONPROCESSORS_VERSION) {
            \PoP\Root\App::getHookManager()->addAction('admin_notices', array($this,'versionWarning'));
            \PoP\Root\App::getHookManager()->addAction('network_admin_notices', array($this,'versionWarning'));
        }

        // if (!defined('POP_GENERICFORMS_VERSION')) {

        //     \PoP\Root\App::getHookManager()->addAction('admin_notices',array($this,'install_warning_2'));
        //     \PoP\Root\App::getHookManager()->addAction('network_admin_notices',array($this,'install_warning_2'));
        //     $success = false;
        // }
        // elseif (!defined('POP_GENERICFORMS_INITIALIZED')) {

        //     \PoP\Root\App::getHookManager()->addAction('admin_notices', array($this, 'initialize_warning_2'));
        //     \PoP\Root\App::getHookManager()->addAction('network_admin_notices', array($this, 'initialize_warning_2'));
        //     $success = false;
        // }
        // elseif (PTW_POP_GENERICFORMS_MIN_VERSION > POP_GENERICFORMS_VERSION) {
            
        //     \PoP\Root\App::getHookManager()->addAction('admin_notices',array($this,'version_warning_2'));
        //     \PoP\Root\App::getHookManager()->addAction('network_admin_notices',array($this,'version_warning_2'));
        // }

        if (!defined('POP_BOOTSTRAPPROCESSORS_VERSION')) {
            \PoP\Root\App::getHookManager()->addAction('admin_notices', array($this,'install_warning_3'));
            \PoP\Root\App::getHookManager()->addAction('network_admin_notices', array($this,'install_warning_3'));
            $success = false;
        } elseif (!defined('POP_BOOTSTRAPPROCESSORS_INITIALIZED')) {
            \PoP\Root\App::getHookManager()->addAction('admin_notices', array($this, 'initialize_warning_3'));
            \PoP\Root\App::getHookManager()->addAction('network_admin_notices', array($this, 'initialize_warning_3'));
            $success = false;
        } elseif (PTW_POP_BOOTSTRAPPROCESSORS_MIN_VERSION > POP_BOOTSTRAPPROCESSORS_VERSION) {
            \PoP\Root\App::getHookManager()->addAction('admin_notices', array($this,'version_warning_3'));
            \PoP\Root\App::getHookManager()->addAction('network_admin_notices', array($this,'version_warning_3'));
        }

        if (!defined('POP_APPLICATIONPROCESSORS_VERSION')) {
            \PoP\Root\App::getHookManager()->addAction('admin_notices', array($this,'install_warning_4'));
            \PoP\Root\App::getHookManager()->addAction('network_admin_notices', array($this,'install_warning_4'));
            $success = false;
        } elseif (!defined('POP_APPLICATIONPROCESSORS_INITIALIZED')) {
            \PoP\Root\App::getHookManager()->addAction('admin_notices', array($this, 'initialize_warning_4'));
            \PoP\Root\App::getHookManager()->addAction('network_admin_notices', array($this, 'initialize_warning_4'));
            $success = false;
        } elseif (PTW_POP_APPLICATIONPROCESSORS_MIN_VERSION > POP_APPLICATIONPROCESSORS_VERSION) {
            \PoP\Root\App::getHookManager()->addAction('admin_notices', array($this,'version_warning_4'));
            \PoP\Root\App::getHookManager()->addAction('network_admin_notices', array($this,'version_warning_4'));
        }

        if (!defined('POP_THEME_VERSION')) {
            \PoP\Root\App::getHookManager()->addAction('admin_notices', array($this,'install_warning_5'));
            \PoP\Root\App::getHookManager()->addAction('network_admin_notices', array($this,'install_warning_5'));
            $success = false;
        } elseif (!defined('POP_THEME_INITIALIZED')) {
            \PoP\Root\App::getHookManager()->addAction('admin_notices', array($this, 'initialize_warning_5'));
            \PoP\Root\App::getHookManager()->addAction('network_admin_notices', array($this, 'initialize_warning_5'));
            $success = false;
        } elseif (PTW_POP_THEME_MIN_VERSION > POP_THEME_VERSION) {
            \PoP\Root\App::getHookManager()->addAction('admin_notices', array($this,'version_warning_5'));
            \PoP\Root\App::getHookManager()->addAction('network_admin_notices', array($this,'version_warning_5'));
        }

        if (!defined('POP_SPAPROCESSORS_VERSION')) {
            \PoP\Root\App::getHookManager()->addAction('admin_notices', array($this,'install_warning_6'));
            \PoP\Root\App::getHookManager()->addAction('network_admin_notices', array($this,'install_warning_6'));
            $success = false;
        } elseif (!defined('POP_SPAPROCESSORS_INITIALIZED')) {
            \PoP\Root\App::getHookManager()->addAction('admin_notices', array($this, 'initialize_warning_6'));
            \PoP\Root\App::getHookManager()->addAction('network_admin_notices', array($this, 'initialize_warning_6'));
            $success = false;
        } elseif (PTW_POP_SPAPROCESSORS_MIN_VERSION > POP_SPAPROCESSORS_VERSION) {
            \PoP\Root\App::getHookManager()->addAction('admin_notices', array($this,'version_warning_6'));
            \PoP\Root\App::getHookManager()->addAction('network_admin_notices', array($this,'version_warning_6'));
        }

        if ($success) {
            // Validate that there is at least one implementation of the CMS
            if (is_null(\PoP\Engine\FunctionAPIFactory::getInstance()) || !is_subclass_of(\PoP\Engine\FunctionAPIFactory::getInstance(), \PoP\Engine\FunctionAPI::class)) {
                \PoP\Root\App::getHookManager()->addAction('admin_notices', array($this, 'cmsimplementationWarning'));
                \PoP\Root\App::getHookManager()->addAction('network_admin_notices', array($this, 'cmsimplementationWarning'));
                $success = false;
            }
        }

        return $success;
    }
    public function initializeWarning()
    {
        $this->dependencyInitializationWarning(
            TranslationAPIFacade::getInstance()->__('PoP Theme Wassup', 'poptheme-wassup'),
            TranslationAPIFacade::getInstance()->__('PoP Application Processors', 'poptheme-wassup')
        );
    }
    public function installWarning()
    {
        $this->dependencyInstallationWarning(
            TranslationAPIFacade::getInstance()->__('PoP Theme Wassup', 'poptheme-wassup'),
            TranslationAPIFacade::getInstance()->__('PoP Application Processors', 'poptheme-wassup'),
            'https://github.com/leoloso/PoP'
        );
    }
    public function versionWarning()
    {
        $this->dependencyVersionWarning(
            TranslationAPIFacade::getInstance()->__('PoP Theme Wassup', 'poptheme-wassup'),
            TranslationAPIFacade::getInstance()->__('PoP Application Processors', 'poptheme-wassup'),
            'https://github.com/leoloso/PoP',
            PTW_POP_APPLICATIONPROCESSORS_MIN_VERSION
        );
    }
    // function initialize_warning_2() {
        
    //     $this->dependencyInitializationWarning(
    //         TranslationAPIFacade::getInstance()->__('PoP Theme Wassup', 'poptheme-wassup'),
    //         TranslationAPIFacade::getInstance()->__('PoP Generic Forms', 'poptheme-wassup')
    //     );
    // }
    // function install_warning_2() {
        
    //     $this->dependencyInstallationWarning(
    //         TranslationAPIFacade::getInstance()->__('PoP Theme Wassup', 'poptheme-wassup'),
    //         TranslationAPIFacade::getInstance()->__('PoP Generic Forms', 'poptheme-wassup'),
    //         'https://github.com/leoloso/PoP'
    //     );
    // }
    // function version_warning_2() {
        
    //     $this->dependencyVersionWarning(
    //         TranslationAPIFacade::getInstance()->__('PoP Theme Wassup', 'poptheme-wassup'),
    //         TranslationAPIFacade::getInstance()->__('PoP Generic Forms', 'poptheme-wassup'),
    //         'https://github.com/leoloso/PoP',
    //         PTW_POP_APPLICATIONPROCESSORS_MIN_VERSION
    //     );
    // }
    public function initialize_warning_3()
    {
        $this->dependencyInitializationWarning(
            TranslationAPIFacade::getInstance()->__('PoP Theme Wassup', 'poptheme-wassup'),
            TranslationAPIFacade::getInstance()->__('PoP Bootstrap Processors', 'poptheme-wassup')
        );
    }
    public function install_warning_3()
    {
        $this->dependencyInstallationWarning(
            TranslationAPIFacade::getInstance()->__('PoP Theme Wassup', 'poptheme-wassup'),
            TranslationAPIFacade::getInstance()->__('PoP Bootstrap Processors', 'poptheme-wassup'),
            'https://github.com/leoloso/PoP'
        );
    }
    public function version_warning_3()
    {
        $this->dependencyVersionWarning(
            TranslationAPIFacade::getInstance()->__('PoP Theme Wassup', 'poptheme-wassup'),
            TranslationAPIFacade::getInstance()->__('PoP Bootstrap Processors', 'poptheme-wassup'),
            'https://github.com/leoloso/PoP',
            PTW_POP_APPLICATIONPROCESSORS_MIN_VERSION
        );
    }
    public function initialize_warning_4()
    {
        $this->dependencyInitializationWarning(
            TranslationAPIFacade::getInstance()->__('PoP Theme Wassup', 'poptheme-wassup'),
            TranslationAPIFacade::getInstance()->__('PoP Application Processors', 'poptheme-wassup')
        );
    }
    public function install_warning_4()
    {
        $this->dependencyInstallationWarning(
            TranslationAPIFacade::getInstance()->__('PoP Theme Wassup', 'poptheme-wassup'),
            TranslationAPIFacade::getInstance()->__('PoP Application Processors', 'poptheme-wassup'),
            'https://github.com/leoloso/PoP'
        );
    }
    public function version_warning_4()
    {
        $this->dependencyVersionWarning(
            TranslationAPIFacade::getInstance()->__('PoP Theme Wassup', 'poptheme-wassup'),
            TranslationAPIFacade::getInstance()->__('PoP Application Processors', 'poptheme-wassup'),
            'https://github.com/leoloso/PoP',
            PTW_POP_APPLICATIONPROCESSORS_MIN_VERSION
        );
    }
    public function initialize_warning_5()
    {
        $this->dependencyInitializationWarning(
            TranslationAPIFacade::getInstance()->__('PoP Theme Wassup', 'poptheme-wassup'),
            TranslationAPIFacade::getInstance()->__('PoP Theme', 'poptheme-wassup')
        );
    }
    public function install_warning_5()
    {
        $this->dependencyInstallationWarning(
            TranslationAPIFacade::getInstance()->__('PoP Theme Wassup', 'poptheme-wassup'),
            TranslationAPIFacade::getInstance()->__('PoP Theme', 'poptheme-wassup'),
            'https://github.com/leoloso/PoP'
        );
    }
    public function version_warning_5()
    {
        $this->dependencyVersionWarning(
            TranslationAPIFacade::getInstance()->__('PoP Theme Wassup', 'poptheme-wassup'),
            TranslationAPIFacade::getInstance()->__('PoP Theme', 'poptheme-wassup'),
            'https://github.com/leoloso/PoP',
            PTW_POP_THEME_MIN_VERSION
        );
    }
    public function initialize_warning_6()
    {
        $this->dependencyInitializationWarning(
            TranslationAPIFacade::getInstance()->__('PoP Theme Wassup', 'poptheme-wassup'),
            TranslationAPIFacade::getInstance()->__('PoP SPA Processors', 'poptheme-wassup')
        );
    }
    public function install_warning_6()
    {
        $this->dependencyInstallationWarning(
            TranslationAPIFacade::getInstance()->__('PoP Theme Wassup', 'poptheme-wassup'),
            TranslationAPIFacade::getInstance()->__('PoP SPA Processors', 'poptheme-wassup'),
            'https://github.com/leoloso/PoP'
        );
    }
    public function version_warning_6()
    {
        $this->dependencyVersionWarning(
            TranslationAPIFacade::getInstance()->__('PoP Theme Wassup', 'poptheme-wassup'),
            TranslationAPIFacade::getInstance()->__('PoP SPA Processors', 'poptheme-wassup'),
            'https://github.com/leoloso/PoP',
            PTW_POP_SPAPROCESSORS_MIN_VERSION
        );
    }
    public function cmsimplementationWarning()
    {
        $this->dependencyImplementationWarning(
            TranslationAPIFacade::getInstance()->__('PoP Theme Wassup', 'poptheme-wassup'),
            TranslationAPIFacade::getInstance()->__('PoP CMS Model', 'poptheme-wassup')
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
    protected function dependencyImplementationWarning($plugin, $dependency)
    {
        $this->adminNotice(
            sprintf(
                TranslationAPIFacade::getInstance()->__('Error: %s', 'pop-engine-webplatform'),
                sprintf(
                    TranslationAPIFacade::getInstance()->__('An underlying CMS implementation for <strong>%s</strong> is not installed/activated. Without it, <strong>%s</strong> will not work.', 'pop-engine-webplatform'),
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
