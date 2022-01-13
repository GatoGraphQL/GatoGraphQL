<?php
use PoP\ComponentModel\Facades\Info\ApplicationInfoFacade;
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoPTheme_Wassup_GF_Install_FormEntries
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addAction(
            'PoP:system-install',
            array($this, 'systemInstall')
        );
    }

    public function systemInstall()
    {

        // Newsletter Entries can defined to be added on system install by PoP version
        if (PoP_Newsletter_GFHelpers::getNewsletterFormId() && defined('POP_GENERICFORMS_GF_SYSTEMINSTALL_NEWSLETTER_ENTRIES') && POP_GENERICFORMS_GF_SYSTEMINSTALL_NEWSLETTER_ENTRIES) {
            foreach (POP_GENERICFORMS_GF_SYSTEMINSTALL_NEWSLETTER_ENTRIES as $version => $entries) {
                // Is the PoP version the right one?
                if ($version == ApplicationInfoFacade::getInstance()->getVersion()) {
                    // Add all entries. Documentation in https://www.gravityhelp.com/documentation/article/api-functions/#add_entries
                    GFAPI::add_entries($entries, PoP_Newsletter_GFHelpers::getNewsletterFormId());
                }
            }
        }
    }
}

/**
 * Initialize
 */
new PoPTheme_Wassup_GF_Install_FormEntries();
