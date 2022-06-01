<?php

class PoP_Module_Processor_LocationContentInners extends PoP_Module_Processor_ContentSingleInnersBase
{
    public final const COMPONENT_TRIGGERTYPEAHEADSELECTINNER_LOCATION = 'triggertypeaheadselectinner-location';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_TRIGGERTYPEAHEADSELECTINNER_LOCATION,
        );
    }

    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getLayoutSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_TRIGGERTYPEAHEADSELECTINNER_LOCATION:
                $ret[] = [PoP_Module_Processor_TriggerLocationTypeaheadScriptLayouts::class, PoP_Module_Processor_TriggerLocationTypeaheadScriptLayouts::COMPONENT_EM_SCRIPT_TRIGGERTYPEAHEADSELECT_LOCATION];
                break;
        }

        return $ret;
    }
}


