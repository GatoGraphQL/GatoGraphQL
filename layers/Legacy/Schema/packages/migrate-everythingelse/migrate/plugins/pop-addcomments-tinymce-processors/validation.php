<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

define('POP_ADDCOMMENTSTINYMCEPROCESSORS_POP_ADDCOMMENTSPROCESSORS_MIN_VERSION', 0.1);
define('POP_ADDCOMMENTSTINYMCEPROCESSORS_POP_ADDCOMMENTSTINYMCE_MIN_VERSION', 0.1);
define('POP_ADDCOMMENTSTINYMCEPROCESSORS_POP_CONTENTCREATIONPROCESSORS_MIN_VERSION', 0.1);

class PoP_AddCommentsTinyMCEProcessors_Validation
{
    public function validate()
    {
        $success = true;
        if (!defined('POP_ADDCOMMENTSPROCESSORS_VERSION')) {
            \PoP\Root\App::addAction('admin_notices', array($this,'installWarning'));
            \PoP\Root\App::addAction('network_admin_notices', array($this,'installWarning'));
            $success = false;
        } elseif (!defined('POP_ADDCOMMENTSPROCESSORS_INITIALIZED')) {
            \PoP\Root\App::addAction('admin_notices', $this->initializeWarning(...));
            \PoP\Root\App::addAction('network_admin_notices', $this->initializeWarning(...));
            $success = false;
        } elseif (POP_ADDCOMMENTSTINYMCEPROCESSORS_POP_ADDCOMMENTSPROCESSORS_MIN_VERSION > POP_ADDCOMMENTSPROCESSORS_VERSION) {
            \PoP\Root\App::addAction('admin_notices', array($this,'versionWarning'));
            \PoP\Root\App::addAction('network_admin_notices', array($this,'versionWarning'));
        }

        if (!defined('POP_ADDCOMMENTSTINYMCE_VERSION')) {
            \PoP\Root\App::addAction('admin_notices', array($this,'install_warning_2'));
            \PoP\Root\App::addAction('network_admin_notices', array($this,'install_warning_2'));
            $success = false;
        } elseif (!defined('POP_ADDCOMMENTSTINYMCE_INITIALIZED')) {
            \PoP\Root\App::addAction('admin_notices', $this->initialize_warning_2(...));
            \PoP\Root\App::addAction('network_admin_notices', $this->initialize_warning_2(...));
            $success = false;
        } elseif (POP_ADDCOMMENTSTINYMCEPROCESSORS_POP_ADDCOMMENTSTINYMCE_MIN_VERSION > POP_ADDCOMMENTSTINYMCE_VERSION) {
            \PoP\Root\App::addAction('admin_notices', array($this,'version_warning_2'));
            \PoP\Root\App::addAction('network_admin_notices', array($this,'version_warning_2'));
        }

        if (!defined('POP_CONTENTCREATIONPROCESSORS_VERSION')) {
            \PoP\Root\App::addAction('admin_notices', array($this,'install_warning_3'));
            \PoP\Root\App::addAction('network_admin_notices', array($this,'install_warning_3'));
            $success = false;
        } elseif (!defined('POP_CONTENTCREATIONPROCESSORS_INITIALIZED')) {
            \PoP\Root\App::addAction('admin_notices', $this->initialize_warning_3(...));
            \PoP\Root\App::addAction('network_admin_notices', $this->initialize_warning_3(...));
            $success = false;
        } elseif (POP_ADDCOMMENTSTINYMCEPROCESSORS_POP_CONTENTCREATIONPROCESSORS_MIN_VERSION > POP_CONTENTCREATIONPROCESSORS_VERSION) {
            \PoP\Root\App::addAction('admin_notices', array($this,'version_warning_3'));
            \PoP\Root\App::addAction('network_admin_notices', array($this,'version_warning_3'));
        }

        return $success;
    }
    public function initializeWarning()
    {
        $this->dependencyInitializationWarning(
            TranslationAPIFacade::getInstance()->__('PoP Add Comments with TinyMCE Processors', 'pop-addcomments-tinymce-processors'),
            TranslationAPIFacade::getInstance()->__('PoP Add Comments Processors', 'pop-addcomments-tinymce-processors')
        );
    }
    public function installWarning()
    {
        $this->dependencyInstallationWarning(
            TranslationAPIFacade::getInstance()->__('PoP Add Comments with TinyMCE Processors', 'pop-addcomments-tinymce-processors'),
            TranslationAPIFacade::getInstance()->__('PoP Add Comments Processors', 'pop-addcomments-tinymce-processors'),
            'https://github.com/leoloso/PoP'
        );
    }
    public function versionWarning()
    {
        $this->dependencyVersionWarning(
            TranslationAPIFacade::getInstance()->__('PoP Add Comments with TinyMCE Processors', 'pop-addcomments-tinymce-processors'),
            TranslationAPIFacade::getInstance()->__('PoP Add Comments Processors', 'pop-addcomments-tinymce-processors'),
            'https://github.com/leoloso/PoP',
            POP_ADDCOMMENTSTINYMCEPROCESSORS_POP_ADDCOMMENTSPROCESSORS_MIN_VERSION
        );
    }
    public function initialize_warning_2()
    {
        $this->dependencyInitializationWarning(
            TranslationAPIFacade::getInstance()->__('PoP Add Comments with TinyMCE Processors', 'pop-addcomments-tinymce-processors'),
            TranslationAPIFacade::getInstance()->__('PoP Add Comments with TinyMCE', 'pop-addcomments-tinymce-processors')
        );
    }
    public function install_warning_2()
    {
        $this->dependencyInstallationWarning(
            TranslationAPIFacade::getInstance()->__('PoP Add Comments with TinyMCE Processors', 'pop-addcomments-tinymce-processors'),
            TranslationAPIFacade::getInstance()->__('PoP Add Comments with TinyMCE', 'pop-addcomments-tinymce-processors'),
            'https://github.com/leoloso/PoP'
        );
    }
    public function version_warning_2()
    {
        $this->dependencyVersionWarning(
            TranslationAPIFacade::getInstance()->__('PoP Add Comments with TinyMCE Processors', 'pop-addcomments-tinymce-processors'),
            TranslationAPIFacade::getInstance()->__('PoP Add Comments with TinyMCE', 'pop-addcomments-tinymce-processors'),
            'https://github.com/leoloso/PoP',
            POP_CONTENTCREATIONTINYMCEPROCESSORS_POP_CONTENTCREATIONPROCESSORS_MIN_VERSION
        );
    }

    public function initialize_warning_3()
    {
        $this->dependencyInitializationWarning(
            TranslationAPIFacade::getInstance()->__('PoP Add Comments with TinyMCE Processors', 'pop-addcomments-tinymce-processors'),
            TranslationAPIFacade::getInstance()->__('PoP Content Creation Processors', 'pop-addcomments-tinymce-processors')
        );
    }
    public function install_warning_3()
    {
        $this->dependencyInstallationWarning(
            TranslationAPIFacade::getInstance()->__('PoP Add Comments with TinyMCE Processors', 'pop-addcomments-tinymce-processors'),
            TranslationAPIFacade::getInstance()->__('PoP Content Creation Processors', 'pop-addcomments-tinymce-processors'),
            'https://github.com/leoloso/PoP'
        );
    }
    public function version_warning_3()
    {
        $this->dependencyVersionWarning(
            TranslationAPIFacade::getInstance()->__('PoP Add Comments with TinyMCE Processors', 'pop-addcomments-tinymce-processors'),
            TranslationAPIFacade::getInstance()->__('PoP Content Creation Processors', 'pop-addcomments-tinymce-processors'),
            'https://github.com/leoloso/PoP',
            POP_ADDCOMMENTSTINYMCEPROCESSORS_POP_CONTENTCREATIONPROCESSORS_MIN_VERSION
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
