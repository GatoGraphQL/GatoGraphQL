<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class PoP_Events_Module_Processor_CustomFilterInners extends PoP_Module_Processor_FilterInnersBase
{
    public const MODULE_FILTERINPUTCONTAINER_EVENTS = 'filterinner-events';
    public const MODULE_FILTERINPUTCONTAINER_AUTHOREVENTS = 'filterinner-authorevents';
    public const MODULE_FILTERINPUTCONTAINER_TAGEVENTS = 'filterinner-tagevents';
    public const MODULE_FILTERINPUTCONTAINER_EVENTSCALENDAR = 'filterinner-eventscalendar';
    public const MODULE_FILTERINPUTCONTAINER_AUTHOREVENTSCALENDAR = 'filterinner-authoreventscalendar';
    public const MODULE_FILTERINPUTCONTAINER_TAGEVENTSCALENDAR = 'filterinner-tageventscalendar';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUTCONTAINER_EVENTS],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_AUTHOREVENTS],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_TAGEVENTS],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_EVENTSCALENDAR],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_AUTHOREVENTSCALENDAR],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_TAGEVENTSCALENDAR],
        );
    }

    protected function getInputSubmodules(array $module)
    {
        $ret = parent::getInputSubmodules($module);

        $inputmodules = [
            self::MODULE_FILTERINPUTCONTAINER_EVENTS => [
                // Events: cannot filter by categories, since em_get_events() has no support for meta_query
                // Events: cannot filter by tags, since using arg "tag" searchs for its own post type for event tag, and not the standard post tag
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_SEARCH],
                [PoP_Events_Module_Processor_SubcomponentFormInputGroups::class, PoP_Events_Module_Processor_SubcomponentFormInputGroups::MODULE_FILTERINPUTGROUP_EVENTSCOPE],
                // [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::MODULE_FILTERINPUTGROUP_CATEGORIES],
                // [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_HASHTAGS],
                [PoP_Module_Processor_FormComponentGroups::class, PoP_Module_Processor_FormComponentGroups::MODULE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_PROFILES],
            ],
            self::MODULE_FILTERINPUTCONTAINER_AUTHOREVENTS => [
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_SEARCH],
                [PoP_Events_Module_Processor_SubcomponentFormInputGroups::class, PoP_Events_Module_Processor_SubcomponentFormInputGroups::MODULE_FILTERINPUTGROUP_EVENTSCOPE],
            ],
            self::MODULE_FILTERINPUTCONTAINER_TAGEVENTS => [
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_SEARCH],
                [PoP_Events_Module_Processor_SubcomponentFormInputGroups::class, PoP_Events_Module_Processor_SubcomponentFormInputGroups::MODULE_FILTERINPUTGROUP_EVENTSCOPE],
                [PoP_Module_Processor_FormComponentGroups::class, PoP_Module_Processor_FormComponentGroups::MODULE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_PROFILES],
            ],
            self::MODULE_FILTERINPUTCONTAINER_EVENTSCALENDAR => [
                // Events: cannot filter by categories, since em_get_events() has no support for meta_query
                // Events: cannot filter by tags, since using arg "tag" searchs for its own post type for event tag, and not the standard post tag
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_SEARCH],
                // [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_HASHTAGS],
                [PoP_Module_Processor_FormComponentGroups::class, PoP_Module_Processor_FormComponentGroups::MODULE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_PROFILES],
            ],
            self::MODULE_FILTERINPUTCONTAINER_AUTHOREVENTSCALENDAR => [
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_SEARCH],
            ],
            self::MODULE_FILTERINPUTCONTAINER_TAGEVENTSCALENDAR => [
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_SEARCH],
                [PoP_Module_Processor_FormComponentGroups::class, PoP_Module_Processor_FormComponentGroups::MODULE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_PROFILES],
            ],
        ];
        if ($modules = HooksAPIFacade::getInstance()->applyFilters(
            'Events:FilterInnerModuleProcessor:inputmodules',
            $inputmodules[$module[1]],
            $module
        )) {
            $ret = array_merge(
                $ret,
                $modules
            );
        }
        return $ret;
    }

    // public function getFilter(array $module)
    // {
    //     $filters = array(
    //         self::MODULE_FILTERINPUTCONTAINER_EVENTS => POP_FILTER_EVENTS,
    //         self::MODULE_FILTERINPUTCONTAINER_AUTHOREVENTS => POP_FILTER_AUTHOREVENTS,
    //         self::MODULE_FILTERINPUTCONTAINER_TAGEVENTS => POP_FILTER_TAGEVENTS,
    //         self::MODULE_FILTERINPUTCONTAINER_EVENTSCALENDAR => POP_FILTER_EVENTSCALENDAR,
    //         self::MODULE_FILTERINPUTCONTAINER_AUTHOREVENTSCALENDAR => POP_FILTER_AUTHOREVENTSCALENDAR,
    //         self::MODULE_FILTERINPUTCONTAINER_TAGEVENTSCALENDAR => POP_FILTER_TAGEVENTSCALENDAR,
    //     );
    //     if ($filter = $filters[$module[1]] ?? null) {
    //         return $filter;
    //     }

    //     return parent::getFilter($module);
    // }
}



