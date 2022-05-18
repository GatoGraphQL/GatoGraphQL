<?php

class PoP_Application_Module_Processor_UserCheckpointMessageAlertLayouts extends PoP_Module_Processor_FeedbackMessageAlertLayoutsBase
{
    public final const MODULE_LAYOUT_CHECKPOINTMESSAGEALERT_DOMAIN = 'layout-checkpointmessagealert-domain';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_CHECKPOINTMESSAGEALERT_DOMAIN],
        );
    }

    public function getLayoutSubmodule(array $component)
    {
        $layouts = array(
            self::COMPONENT_LAYOUT_CHECKPOINTMESSAGEALERT_DOMAIN => [PoP_Application_Module_Processor_UserCheckpointMessageLayouts::class, PoP_Application_Module_Processor_UserCheckpointMessageLayouts::COMPONENT_LAYOUT_CHECKPOINTMESSAGE_DOMAIN],
        );

        if ($layout = $layouts[$component[1]] ?? null) {
            return $layout;
        }

        return parent::getLayoutSubmodule($component);
    }
}



