<?php

trait PoPWebPlatform_AddHighlights_Module_SettingsProcessor_Trait
{
    public function isMultipleopen()
    {
        return array(
            POP_ADDHIGHLIGHTS_ROUTE_ADDHIGHLIGHT => true,
        );
    }
}
