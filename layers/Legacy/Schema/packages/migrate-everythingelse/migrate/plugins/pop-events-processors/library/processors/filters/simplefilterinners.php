<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class PoP_Events_Module_Processor_CustomSimpleFilterInners extends PoP_Module_Processor_SimpleFilterInnersBase
{
    public const MODULE_SIMPLEFILTERINPUTCONTAINER_EVENTS = 'simplefilterinner-events';
    public const MODULE_SIMPLEFILTERINPUTCONTAINER_AUTHOREVENTS = 'simplefilterinner-authorevents';
    public const MODULE_SIMPLEFILTERINPUTCONTAINER_TAGEVENTS = 'simplefilterinner-tagevents';
    public const MODULE_SIMPLEFILTERINPUTCONTAINER_EVENTSCALENDAR = 'simplefilterinner-eventscalendar';
    public const MODULE_SIMPLEFILTERINPUTCONTAINER_AUTHOREVENTSCALENDAR = 'simplefilterinner-authoreventscalendar';
    public const MODULE_SIMPLEFILTERINPUTCONTAINER_TAGEVENTSCALENDAR = 'simplefilterinner-tageventscalendar';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SIMPLEFILTERINPUTCONTAINER_EVENTS],
            [self::class, self::MODULE_SIMPLEFILTERINPUTCONTAINER_AUTHOREVENTS],
            [self::class, self::MODULE_SIMPLEFILTERINPUTCONTAINER_TAGEVENTS],
            [self::class, self::MODULE_SIMPLEFILTERINPUTCONTAINER_EVENTSCALENDAR],
            [self::class, self::MODULE_SIMPLEFILTERINPUTCONTAINER_AUTHOREVENTSCALENDAR],
            [self::class, self::MODULE_SIMPLEFILTERINPUTCONTAINER_TAGEVENTSCALENDAR],
        );
    }

    protected function getInputSubmodules(array $module)
    {
        $ret = parent::getInputSubmodules($module);

        $inputmodules = [
            self::MODULE_SIMPLEFILTERINPUTCONTAINER_EVENTS => [
                // Events: cannot filter by categories, since em_get_events() has no support for meta_query
                // Events: cannot filter by tags, since using arg "tag" searchs for its own post type for event tag, and not the standard post tag
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_SEARCH],
                [PoP_Events_Module_Processor_DateRangeComponentFilterInputs::class, PoP_Events_Module_Processor_DateRangeComponentFilterInputs::MODULE_FILTERINPUT_EVENTSCOPE],
                // [PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::class, PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::MODULE_FILTERINPUT_CATEGORIES],
                // [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_HASHTAGS],
                [PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::class, PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES],
            ],
            self::MODULE_SIMPLEFILTERINPUTCONTAINER_AUTHOREVENTS => [
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_SEARCH],
                [PoP_Events_Module_Processor_DateRangeComponentFilterInputs::class, PoP_Events_Module_Processor_DateRangeComponentFilterInputs::MODULE_FILTERINPUT_EVENTSCOPE],
            ],
            self::MODULE_SIMPLEFILTERINPUTCONTAINER_TAGEVENTS => [
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_SEARCH],
                [PoP_Events_Module_Processor_DateRangeComponentFilterInputs::class, PoP_Events_Module_Processor_DateRangeComponentFilterInputs::MODULE_FILTERINPUT_EVENTSCOPE],
                [PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::class, PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES],
            ],
            self::MODULE_SIMPLEFILTERINPUTCONTAINER_EVENTSCALENDAR => [
                // Events: cannot filter by categories, since em_get_events() has no support for meta_query
                // Events: cannot filter by tags, since using arg "tag" searchs for its own post type for event tag, and not the standard post tag
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_SEARCH],
                // [PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::class, PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::MODULE_FILTERINPUT_CATEGORIES],
                // [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_HASHTAGS],
                [PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::class, PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES],
            ],
            self::MODULE_SIMPLEFILTERINPUTCONTAINER_AUTHOREVENTSCALENDAR => [
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_SEARCH],
            ],
            self::MODULE_SIMPLEFILTERINPUTCONTAINER_TAGEVENTSCALENDAR => [
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
    //         self::MODULE_SIMPLEFILTERINPUTCONTAINER_EVENTS => POP_FILTER_EVENTS,
    //         self::MODULE_SIMPLEFILTERINPUTCONTAINER_AUTHOREVENTS => POP_FILTER_AUTHOREVENTS,
    //         self::MODULE_SIMPLEFILTERINPUTCONTAINER_TAGEVENTS => POP_FILTER_TAGEVENTS,
    //         self::MODULE_SIMPLEFILTERINPUTCONTAINER_EVENTSCALENDAR => POP_FILTER_EVENTSCALENDAR,
    //         self::MODULE_SIMPLEFILTERINPUTCONTAINER_AUTHOREVENTSCALENDAR => POP_FILTER_AUTHOREVENTSCALENDAR,
    //         self::MODULE_SIMPLEFILTERINPUTCONTAINER_TAGEVENTSCALENDAR => POP_FILTER_TAGEVENTSCALENDAR,
    //     );
    //     if ($filter = $filters[$module[1]] ?? null) {
    //         return $filter;
    //     }

    //     return parent::getFilter($module);
    // }
}



