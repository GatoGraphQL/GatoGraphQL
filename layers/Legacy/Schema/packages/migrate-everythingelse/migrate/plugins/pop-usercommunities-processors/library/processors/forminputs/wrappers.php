<?php

class PoP_UserCommunities_Module_Processor_FormInputInputWrappers extends PoP_Module_Processor_ConditionWrapperBase
{
    public final const MODULE_FILTERINPUTWRAPPER_FILTERBYCOMMUNITY = 'filterinputwrapper-communities';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUTWRAPPER_FILTERBYCOMMUNITY],
        );
    }

    public function getConditionSucceededSubmodules(array $componentVariation)
    {
        $ret = parent::getConditionSucceededSubmodules($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_FILTERINPUTWRAPPER_FILTERBYCOMMUNITY:
                $ret[] = [PoP_UserCommunities_Module_Processor_CheckboxFormInputs::class, PoP_UserCommunities_Module_Processor_CheckboxFormInputs::MODULE_FILTERINPUT_FILTERBYCOMMUNITY];
                break;
        }

        return $ret;
    }

    public function getConditionField(array $componentVariation): ?string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FILTERINPUTWRAPPER_FILTERBYCOMMUNITY:
                return 'isCommunity';
        }

        return null;
    }
}



