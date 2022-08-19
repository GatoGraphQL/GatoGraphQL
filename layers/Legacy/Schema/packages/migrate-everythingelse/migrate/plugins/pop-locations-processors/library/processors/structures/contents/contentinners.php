<?php

class PoP_Module_Processor_LocationContentInners extends PoP_Module_Processor_ContentSingleInnersBase
{
    public final const COMPONENT_TRIGGERTYPEAHEADSELECTINNER_LOCATION = 'triggertypeaheadselectinner-location';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_TRIGGERTYPEAHEADSELECTINNER_LOCATION,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component): array
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


