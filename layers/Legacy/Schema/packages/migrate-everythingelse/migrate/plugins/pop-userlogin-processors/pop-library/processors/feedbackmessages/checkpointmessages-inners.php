<?php

class GD_UserLogin_Module_Processor_UserCheckpointMessageInners extends PoP_Module_Processor_CheckpointMessageInnersBase
{
    public final const COMPONENT_CHECKPOINTMESSAGEINNER_NOTLOGGEDIN = 'checkpointmessageinner-notloggedin';
    public final const COMPONENT_CHECKPOINTMESSAGEINNER_LOGGEDIN = 'checkpointmessageinner-loggedin';
    public final const COMPONENT_CHECKPOINTMESSAGEINNER_LOGGEDINCANEDIT = 'checkpointmessageinner-loggedincanedit';
    public final const COMPONENT_CHECKPOINTMESSAGEINNER_LOGGEDINISADMIN = 'checkpointmessageinner-loggedinisadmin';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_CHECKPOINTMESSAGEINNER_NOTLOGGEDIN,
            self::COMPONENT_CHECKPOINTMESSAGEINNER_LOGGEDIN,
            self::COMPONENT_CHECKPOINTMESSAGEINNER_LOGGEDINCANEDIT,
            self::COMPONENT_CHECKPOINTMESSAGEINNER_LOGGEDINISADMIN,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getLayoutSubcomponents($component);

        $layouts = array(
            self::COMPONENT_CHECKPOINTMESSAGEINNER_NOTLOGGEDIN => [GD_UserLogin_Module_Processor_UserCheckpointMessageAlertLayouts::class, GD_UserLogin_Module_Processor_UserCheckpointMessageAlertLayouts::COMPONENT_LAYOUT_CHECKPOINTMESSAGEALERT_NOTLOGGEDIN],
            self::COMPONENT_CHECKPOINTMESSAGEINNER_LOGGEDIN => [GD_UserLogin_Module_Processor_UserCheckpointMessageAlertLayouts::class, GD_UserLogin_Module_Processor_UserCheckpointMessageAlertLayouts::COMPONENT_LAYOUT_CHECKPOINTMESSAGEALERT_LOGGEDIN],
            self::COMPONENT_CHECKPOINTMESSAGEINNER_LOGGEDINCANEDIT => [GD_UserLogin_Module_Processor_UserCheckpointMessageAlertLayouts::class, GD_UserLogin_Module_Processor_UserCheckpointMessageAlertLayouts::COMPONENT_LAYOUT_CHECKPOINTMESSAGEALERT_LOGGEDINCANEDIT],
            self::COMPONENT_CHECKPOINTMESSAGEINNER_LOGGEDINISADMIN => [GD_UserLogin_Module_Processor_UserCheckpointMessageAlertLayouts::class, GD_UserLogin_Module_Processor_UserCheckpointMessageAlertLayouts::COMPONENT_LAYOUT_CHECKPOINTMESSAGEALERT_LOGGEDINISADMIN],
        );

        if ($layout = $layouts[$component->name] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}



