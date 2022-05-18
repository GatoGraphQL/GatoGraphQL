<?php

class GD_URE_Module_Processor_CustomSimpleFilterInners extends PoP_Module_Processor_SimpleFilterInnersBase
{
    public final const COMPONENT_SIMPLEFILTERINPUTCONTAINER_MYMEMBERS = 'simplefilterinputcontainer-mymembers';
    public final const COMPONENT_SIMPLEFILTERINPUTCONTAINER_COMMUNITIES = 'simplefilterinputcontainer-communities';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_MYMEMBERS],
            [self::class, self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_COMMUNITIES],
        );
    }

    protected function getInputSubmodules(array $component)
    {
        $ret = parent::getInputSubmodules($component);

        $inputComponents = [
            self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_MYMEMBERS => [
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::COMPONENT_FILTERINPUT_NAME],
                [GD_URE_Module_Processor_ProfileMultiSelectFilterInputs::class, GD_URE_Module_Processor_ProfileMultiSelectFilterInputs::COMPONENT_URE_FILTERINPUT_MEMBERSTATUS],
                [GD_URE_Module_Processor_ProfileMultiSelectFilterInputs::class, GD_URE_Module_Processor_ProfileMultiSelectFilterInputs::COMPONENT_URE_FILTERINPUT_MEMBERPRIVILEGES],
                [GD_URE_Module_Processor_ProfileMultiSelectFilterInputs::class, GD_URE_Module_Processor_ProfileMultiSelectFilterInputs::COMPONENT_URE_FILTERINPUT_MEMBERTAGS],
                [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::COMPONENT_FILTERINPUT_ORDERUSER],
            ],
            self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_COMMUNITIES => [
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::COMPONENT_FILTERINPUT_NAME],
                [GD_URE_Module_Processor_UserSelectableTypeaheadFilterInputs::class, GD_URE_Module_Processor_UserSelectableTypeaheadFilterInputs::COMPONENT_URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES_USER],
                [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::COMPONENT_FILTERINPUT_ORDERUSER],
            ],
        ];
        if ($components = \PoP\Root\App::applyFilters(
            'UserCommunities:SimpleFilterInners:inputComponents',
            $inputComponents[$component[1]],
            $component
        )) {
            $ret = array_merge(
                $ret,
                $components
            );
        }
        return $ret;
    }

    // public function getFilter(array $component)
    // {
    //     $filters = array(
    //         self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_MYMEMBERS => POP_FILTER_MYMEMBERS,
    //         self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_COMMUNITIES => POP_FILTER_COMMUNITIES,
    //     );
    //     if ($filter = $filters[$component[1]] ?? null) {
    //         return $filter;
    //     }

    //     return parent::getFilter($component);
    // }
}



