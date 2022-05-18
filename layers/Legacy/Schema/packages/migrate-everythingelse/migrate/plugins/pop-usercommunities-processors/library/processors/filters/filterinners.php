<?php

class GD_URE_Module_Processor_CustomFilterInners extends PoP_Module_Processor_FilterInnersBase
{
    public final const MODULE_FILTERINPUTCONTAINER_MYMEMBERS = 'filterinputcontainer-mymembers';
    public final const MODULE_FILTERINPUTCONTAINER_COMMUNITIES = 'filterinputcontainer-communities';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_MYMEMBERS],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_COMMUNITIES],
        );
    }

    protected function getInputSubmodules(array $component)
    {
        $ret = parent::getInputSubmodules($component);

        $inputmodules = [
            self::COMPONENT_FILTERINPUTCONTAINER_MYMEMBERS => [
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::COMPONENT_FILTERINPUTGROUP_NAME],
                [GD_URE_Module_Processor_ProfileFormGroups::class, GD_URE_Module_Processor_ProfileFormGroups::COMPONENT_URE_FILTERINPUTGROUP_MEMBERSTATUS],
                [GD_URE_Module_Processor_ProfileFormGroups::class, GD_URE_Module_Processor_ProfileFormGroups::COMPONENT_URE_FILTERINPUTGROUP_MEMBERPRIVILEGES],
                [GD_URE_Module_Processor_ProfileFormGroups::class, GD_URE_Module_Processor_ProfileFormGroups::COMPONENT_URE_FILTERINPUTGROUP_MEMBERTAGS],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::COMPONENT_FILTERINPUTGROUP_ORDERUSER],
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_COMMUNITIES => [
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::COMPONENT_FILTERINPUTGROUP_NAME],
                [GD_URE_Module_Processor_FormGroups::class, GD_URE_Module_Processor_FormGroups::COMPONENT_URE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITIES_USER],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::COMPONENT_FILTERINPUTGROUP_ORDERUSER],
            ],
        ];
        if ($components = \PoP\Root\App::applyFilters(
            'UserCommunities:FilterInnerComponentProcessor:inputmodules',
            $inputmodules[$component[1]],
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
    //         self::COMPONENT_FILTERINPUTCONTAINER_MYMEMBERS => POP_FILTER_MYMEMBERS,
    //         self::COMPONENT_FILTERINPUTCONTAINER_COMMUNITIES => POP_FILTER_COMMUNITIES,
    //     );
    //     if ($filter = $filters[$component[1]] ?? null) {
    //         return $filter;
    //     }

    //     return parent::getFilter($component);
    // }
}



