<?php

class PoP_CommonUserRoles_Module_Processor_CustomFilterInners extends PoP_Module_Processor_FilterInnersBase
{
    public final const COMPONENT_FILTERINPUTCONTAINER_INDIVIDUALS = 'filterinputcontainer-individuals';
    public final const COMPONENT_FILTERINPUTCONTAINER_ORGANIZATIONS = 'filterinputcontainer-organizations';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_INDIVIDUALS],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_ORGANIZATIONS],
        );
    }

    protected function getInputSubmodules(array $component)
    {
        $ret = parent::getInputSubmodules($component);

        $inputmodules = [
            self::COMPONENT_FILTERINPUTCONTAINER_INDIVIDUALS => [
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::COMPONENT_FILTERINPUTGROUP_NAME],
                [GD_URE_Module_Processor_FormGroups::class, GD_URE_Module_Processor_FormGroups::COMPONENT_URE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITIES_USER],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::COMPONENT_FILTERINPUTGROUP_ORDERUSER],
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_ORGANIZATIONS => [
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::COMPONENT_FILTERINPUTGROUP_NAME],
                [GD_URE_Module_Processor_FormGroups::class, GD_URE_Module_Processor_FormGroups::COMPONENT_URE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITIES_USER],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::COMPONENT_FILTERINPUTGROUP_ORDERUSER],
            ],
        ];
        if ($components = \PoP\Root\App::applyFilters(
            'CommonUserRoles:FilterInnerComponentProcessor:inputmodules',
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
    //         self::COMPONENT_FILTERINPUTCONTAINER_INDIVIDUALS => POP_FILTER_INDIVIDUALS,
    //         self::COMPONENT_FILTERINPUTCONTAINER_ORGANIZATIONS => POP_FILTER_ORGANIZATIONS,
    //     );
    //     if ($filter = $filters[$component[1]] ?? null) {
    //         return $filter;
    //     }

    //     return parent::getFilter($component);
    // }
}



