<?php

class PoP_CommonUserRoles_Module_Processor_UserCheckpointMessageInners extends PoP_Module_Processor_CheckpointMessageInnersBase
{
    public final const MODULE_CHECKPOINTMESSAGEINNER_PROFILEORGANIZATION = 'checkpointmessageinner-profileorganization';
    public final const MODULE_CHECKPOINTMESSAGEINNER_PROFILEINDIVIDUAL = 'checkpointmessageinner-profileindividual';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CHECKPOINTMESSAGEINNER_PROFILEORGANIZATION],
            [self::class, self::MODULE_CHECKPOINTMESSAGEINNER_PROFILEINDIVIDUAL],
        );
    }

    public function getLayoutSubmodules(array $componentVariation)
    {
        $ret = parent::getLayoutSubmodules($componentVariation);

        $layouts = array(
            self::MODULE_CHECKPOINTMESSAGEINNER_PROFILEORGANIZATION => [PoP_CommonUserRoles_Module_Processor_UserCheckpointMessageAlertLayouts::class, PoP_CommonUserRoles_Module_Processor_UserCheckpointMessageAlertLayouts::MODULE_LAYOUT_CHECKPOINTMESSAGEALERT_PROFILEORGANIZATION],
            self::MODULE_CHECKPOINTMESSAGEINNER_PROFILEINDIVIDUAL => [PoP_CommonUserRoles_Module_Processor_UserCheckpointMessageAlertLayouts::class, PoP_CommonUserRoles_Module_Processor_UserCheckpointMessageAlertLayouts::MODULE_LAYOUT_CHECKPOINTMESSAGEALERT_PROFILEINDIVIDUAL],
        );

        if ($layout = $layouts[$componentVariation[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}



