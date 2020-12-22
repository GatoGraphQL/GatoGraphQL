<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class UserStance_DataLoad_FilterHooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter(
            'Stances:FilterInners:inputmodules',
            array($this, 'filtercomponents'),
            10,
            2
        );HooksAPIFacade::getInstance()->addFilter(
            'Stances:SimpleFilterInners:inputmodules',
            array($this, 'simplefiltercomponents'),
            10,
            2
        );
    }

    public function filtercomponents($filterinputs, array $module)
    {
        if (in_array($module, [
            [UserStance_Module_Processor_CustomFilterInners::class, UserStance_Module_Processor_CustomFilterInners::MODULE_FILTERINNER_STANCES],
            [UserStance_Module_Processor_CustomFilterInners::class, UserStance_Module_Processor_CustomFilterInners::MODULE_FILTERINNER_AUTHORSTANCES],
            [UserStance_Module_Processor_CustomFilterInners::class, UserStance_Module_Processor_CustomFilterInners::MODULE_FILTERINNER_STANCES_STANCE],
        ])) {
            array_splice(
                $filterinputs, 
                array_search(
                    [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_SEARCH], 
                    $filterinputs
                )+1, 
                0, 
                [
                    [UserStance_URE_Module_Processor_FormInputGroups::class, UserStance_URE_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_AUTHORROLE_MULTISELECT],
                ]
            );
        }
        return $filterinputs;
    }
    public function simplefiltercomponents($filterinputs, array $module)
    {
        if (in_array($module, [
            [PoPVP_Module_Processor_CustomSimpleFilterInners::class, PoPVP_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINNER_STANCES],
            [PoPVP_Module_Processor_CustomSimpleFilterInners::class, PoPVP_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINNER_AUTHORSTANCES],
            [PoPVP_Module_Processor_CustomSimpleFilterInners::class, PoPVP_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINNER_STANCES_STANCE],
        ])) {
            array_splice(
                $filterinputs, 
                array_search(
                    [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_SEARCH], 
                    $filterinputs
                )+1, 
                0, 
                [
                    [UserStance_URE_Module_Processor_MultiSelectFilterInputs::class, UserStance_URE_Module_Processor_MultiSelectFilterInputs::MODULE_FILTERINPUT_AUTHORROLE_MULTISELECT],
                ]
            );
        }
        return $filterinputs;
    }
}
    
/**
 * Initialize
 */
new UserStance_DataLoad_FilterHooks();
