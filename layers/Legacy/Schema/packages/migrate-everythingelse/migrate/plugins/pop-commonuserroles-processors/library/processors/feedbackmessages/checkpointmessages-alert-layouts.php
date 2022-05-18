<?php

class PoP_CommonUserRoles_Module_Processor_UserCheckpointMessageAlertLayouts extends PoP_Module_Processor_FeedbackMessageAlertLayoutsBase
{
    public final const MODULE_LAYOUT_CHECKPOINTMESSAGEALERT_PROFILEORGANIZATION = 'layout-checkpointmessagealert-profileorganization';
    public final const MODULE_LAYOUT_CHECKPOINTMESSAGEALERT_PROFILEINDIVIDUAL = 'layout-checkpointmessagealert-profileindividual';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_CHECKPOINTMESSAGEALERT_PROFILEORGANIZATION],
            [self::class, self::COMPONENT_LAYOUT_CHECKPOINTMESSAGEALERT_PROFILEINDIVIDUAL],
        );
    }

    public function getLayoutSubmodule(array $component)
    {
        $layouts = array(
            self::COMPONENT_LAYOUT_CHECKPOINTMESSAGEALERT_PROFILEORGANIZATION => [PoP_CommonUserRoles_Module_Processor_UserCheckpointMessageLayouts::class, PoP_CommonUserRoles_Module_Processor_UserCheckpointMessageLayouts::COMPONENT_LAYOUT_CHECKPOINTMESSAGE_PROFILEORGANIZATION],
            self::COMPONENT_LAYOUT_CHECKPOINTMESSAGEALERT_PROFILEINDIVIDUAL => [PoP_CommonUserRoles_Module_Processor_UserCheckpointMessageLayouts::class, PoP_CommonUserRoles_Module_Processor_UserCheckpointMessageLayouts::COMPONENT_LAYOUT_CHECKPOINTMESSAGE_PROFILEINDIVIDUAL],
        );

        if ($layout = $layouts[$component[1]] ?? null) {
            return $layout;
        }

        return parent::getLayoutSubmodule($component);
    }
}



