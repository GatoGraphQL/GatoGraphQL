<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class PoP_Events_Module_Processor_CustomSimpleFilterInners extends PoP_Module_Processor_SimpleFilterInnersBase
{
    public const MODULE_SIMPLEFILTERINNER_EVENTS = 'simplefilterinner-events';
    public const MODULE_SIMPLEFILTERINNER_AUTHOREVENTS = 'simplefilterinner-authorevents';
    public const MODULE_SIMPLEFILTERINNER_TAGEVENTS = 'simplefilterinner-tagevents';
    public const MODULE_SIMPLEFILTERINNER_EVENTSCALENDAR = 'simplefilterinner-eventscalendar';
    public const MODULE_SIMPLEFILTERINNER_AUTHOREVENTSCALENDAR = 'simplefilterinner-authoreventscalendar';
    public const MODULE_SIMPLEFILTERINNER_TAGEVENTSCALENDAR = 'simplefilterinner-tageventscalendar';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SIMPLEFILTERINNER_EVENTS],
            [self::class, self::MODULE_SIMPLEFILTERINNER_AUTHOREVENTS],
            [self::class, self::MODULE_SIMPLEFILTERINNER_TAGEVENTS],
            [self::class, self::MODULE_SIMPLEFILTERINNER_EVENTSCALENDAR],
            [self::class, self::MODULE_SIMPLEFILTERINNER_AUTHOREVENTSCALENDAR],
            [self::class, self::MODULE_SIMPLEFILTERINNER_TAGEVENTSCALENDAR],
        );
    }

    protected function getInputSubmodules(array $module)
    {
        $ret = parent::getInputSubmodules($module);

        $inputmodules = [
            self::MODULE_SIMPLEFILTERINNER_EVENTS => [
                // Events: cannot filter by categories, since em_get_events() has no support for meta_query
                // Events: cannot filter by tags, since using arg "tag" searchs for its own post type for event tag, and not the standard post tag
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_SEARCH],
                [PoP_Events_Module_Processor_DateRangeComponentFilterInputs::class, PoP_Events_Module_Processor_DateRangeComponentFilterInputs::MODULE_FILTERINPUT_EVENTSCOPE],
                // [PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::class, PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::MODULE_FILTERINPUT_CATEGORIES],
                // [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_HASHTAGS],
                [PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::class, PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES],
            ],
            self::MODULE_SIMPLEFILTERINNER_AUTHOREVENTS => [
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_SEARCH],
                [PoP_Events_Module_Processor_DateRangeComponentFilterInputs::class, PoP_Events_Module_Processor_DateRangeComponentFilterInputs::MODULE_FILTERINPUT_EVENTSCOPE],
            ],
            self::MODULE_SIMPLEFILTERINNER_TAGEVENTS => [
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_SEARCH],
                [PoP_Events_Module_Processor_DateRangeComponentFilterInputs::class, PoP_Events_Module_Processor_DateRangeComponentFilterInputs::MODULE_FILTERINPUT_EVENTSCOPE],
                [PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::class, PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES],
            ],
            self::MODULE_SIMPLEFILTERINNER_EVENTSCALENDAR => [
                // Events: cannot filter by categories, since em_get_events() has no support for meta_query
                // Events: cannot filter by tags, since using arg "tag" searchs for its own post type for event tag, and not the standard post tag
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_SEARCH],
                // [PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::class, PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::MODULE_FILTERINPUT_CATEGORIES],
                // [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_HASHTAGS],
                [PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::class, PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES],
            ],
            self::MODULE_SIMPLEFILTERINNER_AUTHOREVENTSCALENDAR => [
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_SEARCH],
            ],
            self::MODULE_SIMPLEFILTERINNER_TAGEVENTSCALENDAR => [
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_SEARCH],
                [PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::class, PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES],
            ],
        ];
        if ($modules = HooksAPIFacade::getInstance()->applyFilters(
            'Events:SimpleFilterInners:inputmodules',
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
    //         self::MODULE_SIMPLEFILTERINNER_EVENTS => POP_FILTER_EVENTS,
    //         self::MODULE_SIMPLEFILTERINNER_AUTHOREVENTS => POP_FILTER_AUTHOREVENTS,
    //         self::MODULE_SIMPLEFILTERINNER_TAGEVENTS => POP_FILTER_TAGEVENTS,
    //         self::MODULE_SIMPLEFILTERINNER_EVENTSCALENDAR => POP_FILTER_EVENTSCALENDAR,
    //         self::MODULE_SIMPLEFILTERINNER_AUTHOREVENTSCALENDAR => POP_FILTER_AUTHOREVENTSCALENDAR,
    //         self::MODULE_SIMPLEFILTERINNER_TAGEVENTSCALENDAR => POP_FILTER_TAGEVENTSCALENDAR,
    //     );
    //     if ($filter = $filters[$module[1]] ?? null) {
    //         return $filter;
    //     }

    //     return parent::getFilter($module);
    // }
}



