<?php

class PoP_CommonUserRoles_Module_Processor_UserCheckpointMessageAlertLayouts extends PoP_Module_Processor_FeedbackMessageAlertLayoutsBase
{
    public final const COMPONENT_LAYOUT_CHECKPOINTMESSAGEALERT_PROFILEORGANIZATION = 'layout-checkpointmessagealert-profileorganization';
    public final const COMPONENT_LAYOUT_CHECKPOINTMESSAGEALERT_PROFILEINDIVIDUAL = 'layout-checkpointmessagealert-profileindividual';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_CHECKPOINTMESSAGEALERT_PROFILEORGANIZATION,
            self::COMPONENT_LAYOUT_CHECKPOINTMESSAGEALERT_PROFILEINDIVIDUAL,
        );
    }

    public function getLayoutSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $layouts = array(
            self::COMPONENT_LAYOUT_CHECKPOINTMESSAGEALERT_PROFILEORGANIZATION => [PoP_CommonUserRoles_Module_Processor_UserCheckpointMessageLayouts::class, PoP_CommonUserRoles_Module_Processor_UserCheckpointMessageLayouts::COMPONENT_LAYOUT_CHECKPOINTMESSAGE_PROFILEORGANIZATION],
            self::COMPONENT_LAYOUT_CHECKPOINTMESSAGEALERT_PROFILEINDIVIDUAL => [PoP_CommonUserRoles_Module_Processor_UserCheckpointMessageLayouts::class, PoP_CommonUserRoles_Module_Processor_UserCheckpointMessageLayouts::COMPONENT_LAYOUT_CHECKPOINTMESSAGE_PROFILEINDIVIDUAL],
        );

        if ($layout = $layouts[$component->name] ?? null) {
            return $layout;
        }

        return parent::getLayoutSubcomponent($component);
    }
}



