<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoPThemeWassup_PreferencesHooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter(
            'PoP_UserPlatform_Preferences:default:values',
            array($this, 'getDefaultPreferencesValues')
        );
    }

    public function getDefaultPreferencesValues($values)
    {

        // Give the default preferences for the theme
        return array_unique(
            array_merge(
                $values,
                array(
                    POP_USERPREFERENCES_EMAILNOTIFICATIONS_GENERAL_SPECIALPOST,
                    POP_USERPREFERENCES_EMAILNOTIFICATIONS_NETWORK_CREATEDCONTENT,
                    POP_USERPREFERENCES_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_CREATEDCONTENT,
                    POP_USERPREFERENCES_EMAILDIGESTS_WEEKLYLATESTPOSTS,
                    POP_USERPREFERENCES_EMAILDIGESTS_WEEKLYUPCOMINGEVENTS,
                    POP_USERPREFERENCES_EMAILDIGESTS_DAILYNOTIFICATIONS,
                    POP_USERPREFERENCES_EMAILDIGESTS_SPECIALPOSTS,
                    // POP_USERPREFERENCES_EMAILDIGESTS_DAILYSUBSCRIBEDTOPICSACTIVITY,
                )
            )
        );
    }
}

/**
 * Initialize
 */
new PoPThemeWassup_PreferencesHooks();
