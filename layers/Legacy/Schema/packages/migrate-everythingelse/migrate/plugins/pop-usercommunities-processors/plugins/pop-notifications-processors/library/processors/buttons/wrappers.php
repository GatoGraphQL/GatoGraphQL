<?php
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;

class Custom_URE_AAL_PoPProcessors_Module_Processor_ButtonWrappers extends PoP_Module_Processor_ConditionWrapperBase
{
    public final const MODULE_UREAAL_BUTTONWRAPPER_EDITMEMBERSHIP = 'ure-aal-buttonwrapper-editmembership';
    public final const MODULE_UREAAL_BUTTONWRAPPER_VIEWALLMEMBERS = 'ure-aal-buttonwrapper-viewallmembers';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_UREAAL_BUTTONWRAPPER_EDITMEMBERSHIP],
            [self::class, self::MODULE_UREAAL_BUTTONWRAPPER_VIEWALLMEMBERS],
        );
    }

    public function getConditionSucceededSubmodules(array $module)
    {
        $ret = parent::getConditionSucceededSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_UREAAL_BUTTONWRAPPER_EDITMEMBERSHIP:
                $ret[] = [Custom_URE_AAL_PoPProcessors_Module_Processor_Buttons::class, Custom_URE_AAL_PoPProcessors_Module_Processor_Buttons::MODULE_UREAAL_BUTTON_EDITMEMBERSHIP];
                break;

            case self::MODULE_UREAAL_BUTTONWRAPPER_VIEWALLMEMBERS:
                $ret[] = [Custom_URE_AAL_PoPProcessors_Module_Processor_Buttons::class, Custom_URE_AAL_PoPProcessors_Module_Processor_Buttons::MODULE_UREAAL_BUTTON_VIEWALLMEMBERS];
                break;
        }

        return $ret;
    }

    public function getConditionFailedSubmodules(array $module)
    {
        $ret = parent::getConditionFailedSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_UREAAL_BUTTONWRAPPER_EDITMEMBERSHIP:
            case self::MODULE_UREAAL_BUTTONWRAPPER_VIEWALLMEMBERS:
                $ret[] = [PoP_Module_Processor_HideIfEmpties::class, PoP_Module_Processor_HideIfEmpties::MODULE_HIDEIFEMPTY];
                break;
        }

        return $ret;
    }

    public function getConditionField(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_UREAAL_BUTTONWRAPPER_EDITMEMBERSHIP:
            case self::MODULE_UREAAL_BUTTONWRAPPER_VIEWALLMEMBERS:
                $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
                return $fieldQueryInterpreter->getField(
                    'equals',
                    [
                        'value1' => $fieldQueryInterpreter->createFieldArgValueAsFieldFromFieldName('objectID'),
                        'value2' => $fieldQueryInterpreter->createFieldArgValueAsFieldFromFieldName('me'),
                    ],
                    'object-id-equals-logged-in-user-id'
                );
        }

        return null;
    }
}



