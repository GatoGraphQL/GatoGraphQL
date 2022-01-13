<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

define('POPCSSC_POP_ENGINE_MIN_VERSION', 0.1);

class PoP_CSSConverter_Validation
{
    public function validate()
    {
        $success = true;
        if (!defined('POP_ENGINE_VERSION')) {
            HooksAPIFacade::getInstance()->addAction('admin_notices', array($this, 'installWarning'));
            HooksAPIFacade::getInstance()->addAction('network_admin_notices', array($this, 'installWarning'));
            $success = false;
        } elseif (!defined('POP_ENGINE_INITIALIZED')) {
            HooksAPIFacade::getInstance()->addAction('admin_notices', array($this, 'initializeWarning'));
            HooksAPIFacade::getInstance()->addAction('network_admin_notices', array($this, 'initializeWarning'));
            $success = false;
        } elseif (POPCSSC_POP_ENGINE_MIN_VERSION > POP_ENGINE_VERSION) {
            HooksAPIFacade::getInstance()->addAction('admin_notices', array($this, 'versionWarning'));
            HooksAPIFacade::getInstance()->addAction('network_admin_notices', array($this, 'versionWarning'));
        }

        // Validate external build tools: Composer
        if (!file_exists(POP_CSSCONVERTER_VENDOR_DIR)) {
            HooksAPIFacade::getInstance()->addAction('admin_notices', array($this, 'buildtoolWarning'));
            HooksAPIFacade::getInstance()->addAction('network_admin_notices', array($this, 'buildtoolWarning'));
            // $success = false;
        }

        return $success;
    }
    public function initializeWarning()
    {
        $this->dependencyInitializationWarning(
            TranslationAPIFacade::getInstance()->__('PoP CSS Converter', 'pop-cssconverter'),
            TranslationAPIFacade::getInstance()->__('PoP Engine', 'pop-cssconverter')
        );
    }
    public function installWarning()
    {
        $this->dependencyInstallationWarning(
            TranslationAPIFacade::getInstance()->__('PoP CSS Converter', 'pop-cssconverter'),
            TranslationAPIFacade::getInstance()->__('PoP Engine', 'pop-cssconverter'),
            'https://github.com/leoloso/PoP'
        );
    }
    public function versionWarning()
    {
        $this->dependencyVersionWarning(
            TranslationAPIFacade::getInstance()->__('PoP CSS Converter', 'pop-cssconverter'),
            TranslationAPIFacade::getInstance()->__('PoP Engine', 'pop-cssconverter'),
            'https://github.com/leoloso/PoP',
            POPCSSC_POP_ENGINE_MIN_VERSION
        );
    }
    public function buildtoolWarning()
    {
        $this->buildtoolInstallationWarning(
            TranslationAPIFacade::getInstance()->__('PoP CSS Converter', 'pop-cssconverter'),
            TranslationAPIFacade::getInstance()->__('PHP CSS Parser', 'pop-cssconverter'),
            'https://github.com/sabberworm/PHP-CSS-Parser'
        );
    }
    protected function buildtoolInstallationWarning($plugin, $library, $plugin_url)
    {
        $this->adminNotice(
            sprintf(
                TranslationAPIFacade::getInstance()->__('Warning: %s', 'pop-engine-webplatform'),
                sprintf(
                    TranslationAPIFacade::getInstance()->__('<strong>%s</strong> depends on library <strong>%s</strong>, which has not been installed. Please install it following <a href="%s">the instructions here</a>, and refresh this page.', 'pop-cssconverter'),
                    $plugin,
                    $library,
                    $plugin_url
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
