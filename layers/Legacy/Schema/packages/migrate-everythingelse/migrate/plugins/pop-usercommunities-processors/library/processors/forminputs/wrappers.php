<?php

class PoP_UserCommunities_Module_Processor_FormInputInputWrappers extends PoP_Module_Processor_ConditionWrapperBase
{
    public final const COMPONENT_FILTERINPUTWRAPPER_FILTERBYCOMMUNITY = 'filterinputwrapper-communities';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FILTERINPUTWRAPPER_FILTERBYCOMMUNITY],
        );
    }

    public function getConditionSucceededSubmodules(array $component)
    {
        $ret = parent::getConditionSucceededSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_FILTERINPUTWRAPPER_FILTERBYCOMMUNITY:
                $ret[] = [PoP_UserCommunities_Module_Processor_CheckboxFormInputs::class, PoP_UserCommunities_Module_Processor_CheckboxFormInputs::COMPONENT_FILTERINPUT_FILTERBYCOMMUNITY];
                break;
        }

        return $ret;
    }

    public function getConditionField(array $component): ?string
    {
        switch ($component[1]) {
            case self::COMPONENT_FILTERINPUTWRAPPER_FILTERBYCOMMUNITY:
                return 'isCommunity';
        }

        return null;
    }
}



