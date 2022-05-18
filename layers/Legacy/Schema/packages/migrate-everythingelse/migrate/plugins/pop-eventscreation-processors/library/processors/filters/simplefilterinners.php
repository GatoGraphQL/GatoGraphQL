<?php

class PoP_EventsCreation_Module_Processor_CustomSimpleFilterInners extends PoP_Module_Processor_SimpleFilterInnersBase
{
    public final const MODULE_SIMPLEFILTERINPUTCONTAINER_MYEVENTS = 'simplefilterinputcontainer-myevents';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SIMPLEFILTERINPUTCONTAINER_MYEVENTS],
        );
    }

    protected function getInputSubmodules(array $module)
    {
        $ret = parent::getInputSubmodules($module);

        $inputmodules = [
            self::MODULE_SIMPLEFILTERINPUTCONTAINER_MYEVENTS => [
                // Events: cannot filter by categories, since em_get_events() has no support for meta_query
                // Events: cannot filter by tags, since using arg "tag" searchs for its own post type for event tag, and not the standard post tag
                GD_CreateUpdate_Utils::moderate() ?
                    [PoP_Module_Processor_MultiSelectFilterInputs::class, PoP_Module_Processor_MultiSelectFilterInputs::MODULE_FILTERINPUT_MODERATEDPOSTSTATUS] :
                    [PoP_Module_Processor_MultiSelectFilterInputs::class, PoP_Module_Processor_MultiSelectFilterInputs::MODULE_FILTERINPUT_UNMODERATEDPOSTSTATUS],
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_SEARCH],
                [PoP_Events_Module_Processor_DateRangeComponentFilterInputs::class, PoP_Events_Module_Processor_DateRangeComponentFilterInputs::MODULE_FILTERINPUT_EVENTSCOPE],
                // [PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::class, PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::MODULE_FILTERINPUT_CATEGORIES],
                // [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_HASHTAGS],
            ],
        ];
        if ($modules = \PoP\Root\App::applyFilters(
            'Events:FilterInnerComponentProcessor:inputmodules',
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
    //         self::MODULE_SIMPLEFILTERINPUTCONTAINER_MYEVENTS => POP_FILTER_MYEVENTS,
    //     );
    //     if ($filter = $filters[$module[1]] ?? null) {
    //         return $filter;
    //     }

    //     return parent::getFilter($module);
    // }
}



