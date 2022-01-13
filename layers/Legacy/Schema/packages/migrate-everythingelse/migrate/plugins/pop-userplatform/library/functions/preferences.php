<?php

class PoP_UserPlatform_Preferences
{
    public function __construct()
    {
        \PoP\Root\App::getHookManager()->addAction(
            'popcms:userRegister',
            array($this, 'grantDefaultPreferences'),
            10,
            1
        );
    }

    /**
     * Returns an array of metakeys with the names of the preferences to tick on
     */
    protected function getDefaultPreferencesValues()
    {
        return \PoP\Root\App::getHookManager()->applyFilters('PoP_UserPlatform_Preferences:default:values', array());
    }

    /**
     * Give the default preferences to the user, after it is created
     */
    public function grantDefaultPreferences($user_id)
    {
        $this->grantDefaultPreferencesToUsers(array($user_id));
    }

    /**
     * Give the default preferences to the users
     */
    public function grantDefaultPreferencesToUsers($user_ids)
    {
        $preferences_values = $this->getDefaultPreferencesValues();
        foreach ($user_ids as $user_id) {
            \PoPSchema\UserMeta\Utils::updateUserMeta($user_id, GD_METAKEY_PROFILE_USERPREFERENCES, $preferences_values);
        }
    }
}

