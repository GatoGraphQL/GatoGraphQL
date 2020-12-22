<?php

trait PoP_ProcessorAutomatedEmails_UserPreferencesTrait
{
    protected function getPreferenceonUsersValue()
    {
        return null;
    }

    protected function getUsers()
    {
        return PoP_UserPlatform_UserPreferencesUtils::getPreferenceonUsers($this->getPreferenceonUsersValue());
    }

    protected function getFrame()
    {
        return POP_EMAILFRAME_PREFERENCES;
    }
}
