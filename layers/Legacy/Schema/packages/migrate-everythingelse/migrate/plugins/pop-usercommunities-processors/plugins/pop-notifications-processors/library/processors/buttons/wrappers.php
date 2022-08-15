<?php
class Custom_URE_AAL_PoPProcessors_Module_Processor_ButtonWrappers extends PoP_Module_Processor_ConditionWrapperBase
{
    public final const COMPONENT_UREAAL_BUTTONWRAPPER_EDITMEMBERSHIP = 'ure-aal-buttonwrapper-editmembership';
    public final const COMPONENT_UREAAL_BUTTONWRAPPER_VIEWALLMEMBERS = 'ure-aal-buttonwrapper-viewallmembers';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_UREAAL_BUTTONWRAPPER_EDITMEMBERSHIP,
            self::COMPONENT_UREAAL_BUTTONWRAPPER_VIEWALLMEMBERS,
        );
    }

    public function getConditionSucceededSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getConditionSucceededSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_UREAAL_BUTTONWRAPPER_EDITMEMBERSHIP:
                $ret[] = [Custom_URE_AAL_PoPProcessors_Module_Processor_Buttons::class, Custom_URE_AAL_PoPProcessors_Module_Processor_Buttons::COMPONENT_UREAAL_BUTTON_EDITMEMBERSHIP];
                break;

            case self::COMPONENT_UREAAL_BUTTONWRAPPER_VIEWALLMEMBERS:
                $ret[] = [Custom_URE_AAL_PoPProcessors_Module_Processor_Buttons::class, Custom_URE_AAL_PoPProcessors_Module_Processor_Buttons::COMPONENT_UREAAL_BUTTON_VIEWALLMEMBERS];
                break;
        }

        return $ret;
    }

    public function getConditionFailedSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getConditionFailedSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_UREAAL_BUTTONWRAPPER_EDITMEMBERSHIP:
            case self::COMPONENT_UREAAL_BUTTONWRAPPER_VIEWALLMEMBERS:
                $ret[] = [PoP_Module_Processor_HideIfEmpties::class, PoP_Module_Processor_HideIfEmpties::COMPONENT_HIDEIFEMPTY];
                break;
        }

        return $ret;
    }

    public function getConditionField(\PoP\ComponentModel\Component\Component $component): ?string
    {
        switch ($component->name) {
            case self::COMPONENT_UREAAL_BUTTONWRAPPER_EDITMEMBERSHIP:
            case self::COMPONENT_UREAAL_BUTTONWRAPPER_VIEWALLMEMBERS:
                return /* @todo Re-do this code! Left undone */ new Field(
                    'equals',
                    [
                        'value1' => 'objectID()', /* @todo @todo Re-do this code! Use ResolvedVariableReference */
                        'value2' => 'me()', /* @todo @todo Re-do this code! Use ResolvedVariableReference */
                    ],
                    'object-id-equals-logged-in-user-id'
                );
        }

        return null;
    }
}



