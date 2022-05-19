<?php

class PoP_EventsCreation_Module_Processor_CustomFilterInners extends PoP_Module_Processor_FilterInnersBase
{
    public final const COMPONENT_FILTERINPUTCONTAINER_MYEVENTS = 'filterinputcontainer-myevents';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_MYEVENTS],
        );
    }

    protected function getInputSubcomponents(array $component)
    {
        $ret = parent::getInputSubcomponents($component);

        $inputComponents = [
            self::COMPONENT_FILTERINPUTCONTAINER_MYEVENTS => [
                // Events: cannot filter by categories, since em_get_events() has no support for meta_query
                // Events: cannot filter by tags, since using arg "tag" searchs for its own post type for event tag, and not the standard post tag
                GD_CreateUpdate_Utils::moderate() ?
                    [GD_Core_Bootstrap_Module_Processor_FormInputGroups::class, GD_Core_Bootstrap_Module_Processor_FormInputGroups::COMPONENT_FILTERINPUTGROUP_MODERATEDPOSTSTATUS] :
                    [GD_Core_Bootstrap_Module_Processor_FormInputGroups::class, GD_Core_Bootstrap_Module_Processor_FormInputGroups::COMPONENT_FILTERINPUTGROUP_UNMODERATEDPOSTSTATUS],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::COMPONENT_FILTERINPUTGROUP_SEARCH],
                [PoP_Events_Module_Processor_SubcomponentFormInputGroups::class, PoP_Events_Module_Processor_SubcomponentFormInputGroups::COMPONENT_FILTERINPUTGROUP_EVENTSCOPE],
                // [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::COMPONENT_FILTERINPUTGROUP_CATEGORIES],
                // [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::COMPONENT_FILTERINPUTGROUP_HASHTAGS],
            ],
        ];
        if ($components = \PoP\Root\App::applyFilters(
            'Events:FilterInnerComponentProcessor:inputComponents',
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
    //         self::COMPONENT_FILTERINPUTCONTAINER_MYEVENTS => POP_FILTER_MYEVENTS,
    //     );
    //     if ($filter = $filters[$component[1]] ?? null) {
    //         return $filter;
    //     }

    //     return parent::getFilter($component);
    // }
}



