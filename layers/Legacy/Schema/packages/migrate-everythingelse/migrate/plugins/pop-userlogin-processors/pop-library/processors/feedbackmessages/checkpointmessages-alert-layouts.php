<?php

class GD_UserLogin_Module_Processor_UserCheckpointMessageAlertLayouts extends PoP_Module_Processor_FeedbackMessageAlertLayoutsBase
{
    public final const MODULE_LAYOUT_CHECKPOINTMESSAGEALERT_NOTLOGGEDIN = 'layout-checkpointmessagealert-notloggedin';
    public final const MODULE_LAYOUT_CHECKPOINTMESSAGEALERT_LOGGEDIN = 'layout-checkpointmessagealert-loggedin';
    public final const MODULE_LAYOUT_CHECKPOINTMESSAGEALERT_LOGGEDINCANEDIT = 'layout-checkpointmessagealert-loggedincanedit';
    public final const MODULE_LAYOUT_CHECKPOINTMESSAGEALERT_LOGGEDINISADMIN = 'layout-checkpointmessagealert-loggedinisadmin';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_CHECKPOINTMESSAGEALERT_NOTLOGGEDIN],
            [self::class, self::COMPONENT_LAYOUT_CHECKPOINTMESSAGEALERT_LOGGEDIN],
            [self::class, self::COMPONENT_LAYOUT_CHECKPOINTMESSAGEALERT_LOGGEDINCANEDIT],
            [self::class, self::COMPONENT_LAYOUT_CHECKPOINTMESSAGEALERT_LOGGEDINISADMIN],
        );
    }

    public function getLayoutSubmodule(array $component)
    {
        $layouts = array(
            self::COMPONENT_LAYOUT_CHECKPOINTMESSAGEALERT_NOTLOGGEDIN => [GD_UserLogin_Module_Processor_UserCheckpointMessageLayouts::class, GD_UserLogin_Module_Processor_UserCheckpointMessageLayouts::COMPONENT_LAYOUT_CHECKPOINTMESSAGE_NOTLOGGEDIN],
            self::COMPONENT_LAYOUT_CHECKPOINTMESSAGEALERT_LOGGEDIN => [GD_UserLogin_Module_Processor_UserCheckpointMessageLayouts::class, GD_UserLogin_Module_Processor_UserCheckpointMessageLayouts::COMPONENT_LAYOUT_CHECKPOINTMESSAGE_LOGGEDIN],
            self::COMPONENT_LAYOUT_CHECKPOINTMESSAGEALERT_LOGGEDINCANEDIT => [GD_UserLogin_Module_Processor_UserCheckpointMessageLayouts::class, GD_UserLogin_Module_Processor_UserCheckpointMessageLayouts::COMPONENT_LAYOUT_CHECKPOINTMESSAGE_LOGGEDINCANEDIT],
            self::COMPONENT_LAYOUT_CHECKPOINTMESSAGEALERT_LOGGEDINISADMIN => [GD_UserLogin_Module_Processor_UserCheckpointMessageLayouts::class, GD_UserLogin_Module_Processor_UserCheckpointMessageLayouts::COMPONENT_LAYOUT_CHECKPOINTMESSAGE_LOGGEDINISADMIN],
        );

        if ($layout = $layouts[$component[1]] ?? null) {
            return $layout;
        }

        return parent::getLayoutSubmodule($component);
    }
}



