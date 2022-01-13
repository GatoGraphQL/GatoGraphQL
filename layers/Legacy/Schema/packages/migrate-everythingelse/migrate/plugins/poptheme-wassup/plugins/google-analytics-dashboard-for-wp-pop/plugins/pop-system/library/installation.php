<?php
use PoP\ComponentModel\Facades\Info\ApplicationInfoFacade;
class PoPTheme_Wassup_GADWP_Installation
{
    public function __construct()
    {
        \PoP\Root\App::getHookManager()->addAction('PoP:system-install', array($this,'updateSettings'));
    }

    public function updateSettings()
    {

        // If this is the indicated version of the app, then update the plugin settings
        if (ApplicationInfoFacade::getInstance()->getVersion() == POPTHEME_WASSUP_GADWP_PLUGINUPDATE_VERSION) {
            // Set the tracking scripts in the footer, not in the header
            // Code copied from `private static function update_options( $who )` in wp-content/plugins/google-analytics-dashboard-for-wp/admin/settings.php
            $gadwp = GADWP();
            
            // Set the new options
            $gadwp->config->options['trackingcode_infooter'] = 1;
            $gadwp->config->options['trackingevents_infooter'] = 1;
            $gadwp->config->set_plugin_options(false);
        }
    }
}

/**
 * Initialization
 */
new PoPTheme_Wassup_GADWP_Installation();
