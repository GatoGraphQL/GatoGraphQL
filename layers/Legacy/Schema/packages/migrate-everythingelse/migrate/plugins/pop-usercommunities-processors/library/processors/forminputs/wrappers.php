<?php

class PoP_UserCommunities_Module_Processor_FormInputInputWrappers extends PoP_Module_Processor_ConditionWrapperBase
{
    public final const COMPONENT_FILTERINPUTWRAPPER_FILTERBYCOMMUNITY = 'filterinputwrapper-communities';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FILTERINPUTWRAPPER_FILTERBYCOMMUNITY,
        );
    }

    public function getConditionSucceededSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getConditionSucceededSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_FILTERINPUTWRAPPER_FILTERBYCOMMUNITY:
                $ret[] = [PoP_UserCommunities_Module_Processor_CheckboxFormInputs::class, PoP_UserCommunities_Module_Processor_CheckboxFormInputs::COMPONENT_FILTERINPUT_FILTERBYCOMMUNITY];
                break;
        }

        return $ret;
    }

    public function getConditionField(\PoP\ComponentModel\Component\Component $component): ?string
    {
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUTWRAPPER_FILTERBYCOMMUNITY:
                return 'isCommunity';
        }

        return null;
    }
}



