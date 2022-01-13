<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

define('QTXPOP_QTX_MIN_VERSION', '3.4.6.8');

class QTX_PoP_Validation
{
    public function validate()
    {
        $success = true;

        // Validate plug-in
        if (!class_exists('QTX_Translator')) {
            \PoP\Root\App::addAction('admin_notices', array($this,'pluginWarning'));
            \PoP\Root\App::addAction('network_admin_notices', array($this,'pluginWarning'));
            $success = false;
        } elseif (QTXPOP_QTX_MIN_VERSION > QTX_VERSION) {
            \PoP\Root\App::addAction('admin_notices', array($this,'pluginversion_warning'));
            \PoP\Root\App::addAction('network_admin_notices', array($this,'pluginversion_warning'));
        }

        return $success;
    }
    public function pluginWarning()
    {
        $this->dependencyInstallationWarning(
            TranslationAPIFacade::getInstance()->__('qTranslate-X for PoP', 'qtx-pop'),
            TranslationAPIFacade::getInstance()->__('qTranslate-X', 'qtx-pop'),
            'https://wordpress.org/plugins/qtranslate-x/'
        );
    }
    public function pluginversion_warning()
    {
        $this->dependencyVersionWarning(
            TranslationAPIFacade::getInstance()->__('qTranslate-X for PoP', 'qtx-pop'),
            TranslationAPIFacade::getInstance()->__('qTranslate-X', 'qtx-pop'),
            'https://wordpress.org/plugins/qtranslate-x/',
            QTXPOP_QTX_MIN_VERSION
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
