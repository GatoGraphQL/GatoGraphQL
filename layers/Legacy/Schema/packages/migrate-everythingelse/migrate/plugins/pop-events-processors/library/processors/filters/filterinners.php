<?php

class PoP_Events_Module_Processor_CustomFilterInners extends PoP_Module_Processor_FilterInnersBase
{
    public final const COMPONENT_FILTERINPUTCONTAINER_EVENTS = 'filterinputcontainer-events';
    public final const COMPONENT_FILTERINPUTCONTAINER_AUTHOREVENTS = 'filterinputcontainer-authorevents';
    public final const COMPONENT_FILTERINPUTCONTAINER_TAGEVENTS = 'filterinputcontainer-tagevents';
    public final const COMPONENT_FILTERINPUTCONTAINER_EVENTSCALENDAR = 'filterinputcontainer-eventscalendar';
    public final const COMPONENT_FILTERINPUTCONTAINER_AUTHOREVENTSCALENDAR = 'filterinputcontainer-authoreventscalendar';
    public final const COMPONENT_FILTERINPUTCONTAINER_TAGEVENTSCALENDAR = 'filterinputcontainer-tageventscalendar';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_EVENTS],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_AUTHOREVENTS],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_TAGEVENTS],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_EVENTSCALENDAR],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_AUTHOREVENTSCALENDAR],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_TAGEVENTSCALENDAR],
        );
    }

    protected function getInputSubmodules(array $component)
    {
        $ret = parent::getInputSubmodules($component);

        $inputmodules = [
            self::COMPONENT_FILTERINPUTCONTAINER_EVENTS => [
                // Events: cannot filter by categories, since em_get_events() has no support for meta_query
                // Events: cannot filter by tags, since using arg "tag" searchs for its own post type for event tag, and not the standard post tag
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::COMPONENT_FILTERINPUTGROUP_SEARCH],
                [PoP_Events_Module_Processor_SubcomponentFormInputGroups::class, PoP_Events_Module_Processor_SubcomponentFormInputGroups::COMPONENT_FILTERINPUTGROUP_EVENTSCOPE],
                // [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::COMPONENT_FILTERINPUTGROUP_CATEGORIES],
                // [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::COMPONENT_FILTERINPUTGROUP_HASHTAGS],
                [PoP_Module_Processor_FormComponentGroups::class, PoP_Module_Processor_FormComponentGroups::COMPONENT_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_PROFILES],
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_AUTHOREVENTS => [
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::COMPONENT_FILTERINPUTGROUP_SEARCH],
                [PoP_Events_Module_Processor_SubcomponentFormInputGroups::class, PoP_Events_Module_Processor_SubcomponentFormInputGroups::COMPONENT_FILTERINPUTGROUP_EVENTSCOPE],
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_TAGEVENTS => [
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::COMPONENT_FILTERINPUTGROUP_SEARCH],
                [PoP_Events_Module_Processor_SubcomponentFormInputGroups::class, PoP_Events_Module_Processor_SubcomponentFormInputGroups::COMPONENT_FILTERINPUTGROUP_EVENTSCOPE],
                [PoP_Module_Processor_FormComponentGroups::class, PoP_Module_Processor_FormComponentGroups::COMPONENT_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_PROFILES],
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_EVENTSCALENDAR => [
                // Events: cannot filter by categories, since em_get_events() has no support for meta_query
                // Events: cannot filter by tags, since using arg "tag" searchs for its own post type for event tag, and not the standard post tag
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::COMPONENT_FILTERINPUTGROUP_SEARCH],
                // [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::COMPONENT_FILTERINPUTGROUP_HASHTAGS],
                [PoP_Module_Processor_FormComponentGroups::class, PoP_Module_Processor_FormComponentGroups::COMPONENT_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_PROFILES],
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_AUTHOREVENTSCALENDAR => [
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::COMPONENT_FILTERINPUTGROUP_SEARCH],
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_TAGEVENTSCALENDAR => [
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::COMPONENT_FILTERINPUTGROUP_SEARCH],
                [PoP_Module_Processor_FormComponentGroups::class, PoP_Module_Processor_FormComponentGroups::COMPONENT_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_PROFILES],
            ],
        ];
        if ($components = \PoP\Root\App::applyFilters(
            'Events:FilterInnerComponentProcessor:inputmodules',
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
    //         self::COMPONENT_FILTERINPUTCONTAINER_EVENTS => POP_FILTER_EVENTS,
    //         self::COMPONENT_FILTERINPUTCONTAINER_AUTHOREVENTS => POP_FILTER_AUTHOREVENTS,
    //         self::COMPONENT_FILTERINPUTCONTAINER_TAGEVENTS => POP_FILTER_TAGEVENTS,
    //         self::COMPONENT_FILTERINPUTCONTAINER_EVENTSCALENDAR => POP_FILTER_EVENTSCALENDAR,
    //         self::COMPONENT_FILTERINPUTCONTAINER_AUTHOREVENTSCALENDAR => POP_FILTER_AUTHOREVENTSCALENDAR,
    //         self::COMPONENT_FILTERINPUTCONTAINER_TAGEVENTSCALENDAR => POP_FILTER_TAGEVENTSCALENDAR,
    //     );
    //     if ($filter = $filters[$component[1]] ?? null) {
    //         return $filter;
    //     }

    //     return parent::getFilter($component);
    // }
}



