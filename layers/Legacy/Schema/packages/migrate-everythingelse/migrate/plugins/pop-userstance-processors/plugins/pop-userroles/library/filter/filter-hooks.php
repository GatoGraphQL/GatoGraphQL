<?php

class UserStance_DataLoad_FilterHooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'Stances:FilterInnerComponentProcessor:inputmodules',
            $this->filtercomponents(...),
            10,
            2
        );\PoP\Root\App::addFilter(
            'Stances:SimpleFilterInners:inputmodules',
            $this->simplefiltercomponents(...),
            10,
            2
        );
    }

    public function filtercomponents($filterinputs, array $component)
    {
        if (in_array($component, [
            [UserStance_Module_Processor_CustomFilterInners::class, UserStance_Module_Processor_CustomFilterInners::COMPONENT_FILTERINPUTCONTAINER_STANCES],
            [UserStance_Module_Processor_CustomFilterInners::class, UserStance_Module_Processor_CustomFilterInners::COMPONENT_FILTERINPUTCONTAINER_AUTHORSTANCES],
            [UserStance_Module_Processor_CustomFilterInners::class, UserStance_Module_Processor_CustomFilterInners::COMPONENT_FILTERINPUTCONTAINER_STANCES_STANCE],
        ])) {
            array_splice(
                $filterinputs,
                array_search(
                    [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::COMPONENT_FILTERINPUTGROUP_SEARCH],
                    $filterinputs
                )+1,
                0,
                [
                    [UserStance_URE_Module_Processor_FormInputGroups::class, UserStance_URE_Module_Processor_FormInputGroups::COMPONENT_FILTERINPUTGROUP_AUTHORROLE_MULTISELECT],
                ]
            );
        }
        return $filterinputs;
    }
    public function simplefiltercomponents($filterinputs, array $component)
    {
        if (in_array($component, [
            [PoPVP_Module_Processor_CustomSimpleFilterInners::class, PoPVP_Module_Processor_CustomSimpleFilterInners::COMPONENT_SIMPLEFILTERINPUTCONTAINER_STANCES],
            [PoPVP_Module_Processor_CustomSimpleFilterInners::class, PoPVP_Module_Processor_CustomSimpleFilterInners::COMPONENT_SIMPLEFILTERINPUTCONTAINER_AUTHORSTANCES],
            [PoPVP_Module_Processor_CustomSimpleFilterInners::class, PoPVP_Module_Processor_CustomSimpleFilterInners::COMPONENT_SIMPLEFILTERINPUTCONTAINER_STANCES_STANCE],
        ])) {
            array_splice(
                $filterinputs,
                array_search(
                    [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::COMPONENT_FILTERINPUT_SEARCH],
                    $filterinputs
                )+1,
                0,
                [
                    [UserStance_URE_Module_Processor_MultiSelectFilterInputs::class, UserStance_URE_Module_Processor_MultiSelectFilterInputs::COMPONENT_FILTERINPUT_AUTHORROLE_MULTISELECT],
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
