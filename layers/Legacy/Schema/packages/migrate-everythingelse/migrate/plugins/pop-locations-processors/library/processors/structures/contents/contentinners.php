<?php

class PoP_Module_Processor_LocationContentInners extends PoP_Module_Processor_ContentSingleInnersBase
{
    public final const MODULE_TRIGGERTYPEAHEADSELECTINNER_LOCATION = 'triggertypeaheadselectinner-location';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_TRIGGERTYPEAHEADSELECTINNER_LOCATION],
        );
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        switch ($component[1]) {
            case self::MODULE_TRIGGERTYPEAHEADSELECTINNER_LOCATION:
                $ret[] = [PoP_Module_Processor_TriggerLocationTypeaheadScriptLayouts::class, PoP_Module_Processor_TriggerLocationTypeaheadScriptLayouts::MODULE_EM_SCRIPT_TRIGGERTYPEAHEADSELECT_LOCATION];
                break;
        }

        return $ret;
    }
}


