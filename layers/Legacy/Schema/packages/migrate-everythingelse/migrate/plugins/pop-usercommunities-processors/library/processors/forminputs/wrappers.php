<?php

class PoP_UserCommunities_Module_Processor_FormInputInputWrappers extends PoP_Module_Processor_ConditionWrapperBase
{
    public final const MODULE_FILTERINPUTWRAPPER_FILTERBYCOMMUNITY = 'filterinputwrapper-communities';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUTWRAPPER_FILTERBYCOMMUNITY],
        );
    }

    public function getConditionSucceededSubmodules(array $module)
    {
        $ret = parent::getConditionSucceededSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_FILTERINPUTWRAPPER_FILTERBYCOMMUNITY:
                $ret[] = [PoP_UserCommunities_Module_Processor_CheckboxFormInputs::class, PoP_UserCommunities_Module_Processor_CheckboxFormInputs::MODULE_FILTERINPUT_FILTERBYCOMMUNITY];
                break;
        }

        return $ret;
    }

    public function getConditionField(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUTWRAPPER_FILTERBYCOMMUNITY:
                return 'isCommunity';
        }

        return null;
    }
}



