<?php

trait PoP_WebPlatformEngine_Module_DefaultSettingsProcessorTrait
{
    public function silentDocument()
    {

        // Silent document? (Opposite to Update the browser URL and Title?)
        // Not silent by default. Yes if it is quickview, or if in the customsettings it said so for that page (eg: Loggedinuser-data)
        return false;
    }

    public function isMultipleopen()
    {

        // Can open several instances of this page in the client? (eg: Add Post)
        return false;
    }

    public function isAppshell()
    {
        return false;
    }

    public function storeLocal()
    {
        return false;
    }
}
