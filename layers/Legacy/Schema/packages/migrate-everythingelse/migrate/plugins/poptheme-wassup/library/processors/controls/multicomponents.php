<?php

class PoP_Module_Processor_ControlMulticomponents extends PoP_Module_Processor_MultiplesBase
{
    public final const COMPONENT_MULTICOMPONENT_ANCHORCONTROL_TOGGLETABS = 'multicomponent-anchorcontrol-toggletabs';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_MULTICOMPONENT_ANCHORCONTROL_TOGGLETABS,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_MULTICOMPONENT_ANCHORCONTROL_TOGGLETABS:
                $ret[] = [GD_Wassup_Module_Processor_AnchorControls::class, GD_Wassup_Module_Processor_AnchorControls::COMPONENT_ANCHORCONTROL_TOGGLETABS];
                $ret[] = [GD_Wassup_Module_Processor_AnchorControls::class, GD_Wassup_Module_Processor_AnchorControls::COMPONENT_ANCHORCONTROL_TOGGLETABSXS];
                break;
        }

        return $ret;
    }
}



