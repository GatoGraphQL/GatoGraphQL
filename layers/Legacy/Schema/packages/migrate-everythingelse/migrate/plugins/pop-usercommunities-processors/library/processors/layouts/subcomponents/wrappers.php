<?php

class GD_URE_Module_Processor_MembersLayoutWrappers extends PoP_Module_Processor_ConditionWrapperBase
{
    public final const MODULE_URE_LAYOUTWRAPPER_COMMUNITYMEMBERS = 'ure-layoutwrapper-communitymembers';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_URE_LAYOUTWRAPPER_COMMUNITYMEMBERS],
        );
    }

    public function getConditionSucceededSubmodules(array $module)
    {
        $ret = parent::getConditionSucceededSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_URE_LAYOUTWRAPPER_COMMUNITYMEMBERS:
                $ret[] = [GD_URE_Module_Processor_Codes::class, GD_URE_Module_Processor_Codes::MODULE_URE_CODE_MEMBERSLABEL];
                $ret[] = [GD_URE_Module_Processor_MembersLayoutMultipleComponents::class, GD_URE_Module_Processor_MembersLayoutMultipleComponents::MODULE_URE_MULTICOMPONENT_COMMUNITYMEMBERS];
                break;
        }

        return $ret;
    }

    public function getConditionField(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_URE_LAYOUTWRAPPER_COMMUNITYMEMBERS:
                return 'hasMembers';
        }

        return null;
    }
}



