<?php

class PoP_Application_Module_Processor_UserCheckpointMessageInners extends PoP_Module_Processor_CheckpointMessageInnersBase
{
    public final const COMPONENT_CHECKPOINTMESSAGEINNER_DOMAIN = 'checkpointmessageinner-domain';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_CHECKPOINTMESSAGEINNER_DOMAIN,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getLayoutSubcomponents($component);

        $layouts = array(
            self::COMPONENT_CHECKPOINTMESSAGEINNER_DOMAIN => [PoP_Application_Module_Processor_UserCheckpointMessageAlertLayouts::class, PoP_Application_Module_Processor_UserCheckpointMessageAlertLayouts::COMPONENT_LAYOUT_CHECKPOINTMESSAGEALERT_DOMAIN],
        );

        if ($layout = $layouts[$component->name] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}



