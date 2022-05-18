<?php

class PoP_AddHighlights_Module_Processor_CustomSimpleFilterInners extends PoP_Module_Processor_SimpleFilterInnersBase
{
    public final const MODULE_SIMPLEFILTERINPUTCONTAINER_HIGHLIGHTS = 'simplefilterinputcontainer-highlights';
    public final const MODULE_SIMPLEFILTERINPUTCONTAINER_AUTHORHIGHLIGHTS = 'simplefilterinputcontainer-authorhighlights';
    public final const MODULE_SIMPLEFILTERINPUTCONTAINER_MYHIGHLIGHTS = 'simplefilterinputcontainer-myhighlights';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SIMPLEFILTERINPUTCONTAINER_HIGHLIGHTS],
            [self::class, self::MODULE_SIMPLEFILTERINPUTCONTAINER_AUTHORHIGHLIGHTS],
            [self::class, self::MODULE_SIMPLEFILTERINPUTCONTAINER_MYHIGHLIGHTS],
        );
    }

    protected function getInputSubmodules(array $component)
    {
        $ret = parent::getInputSubmodules($component);

        $inputmodules = [
            self::MODULE_SIMPLEFILTERINPUTCONTAINER_HIGHLIGHTS => [
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_SEARCH],
                [PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::class, PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES],
                [PoP_Module_Processor_DateRangeComponentFilterInputs::class, PoP_Module_Processor_DateRangeComponentFilterInputs::MODULE_FILTERINPUT_CUSTOMPOSTDATES],
                [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::MODULE_FILTERINPUT_ORDERPOST],
            ],
            self::MODULE_SIMPLEFILTERINPUTCONTAINER_AUTHORHIGHLIGHTS => [
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_SEARCH],
                [PoP_Module_Processor_PostSelectableTypeaheadFilterComponents::class, PoP_Module_Processor_PostSelectableTypeaheadFilterComponents::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES],
                [PoP_Module_Processor_DateRangeComponentFilterInputs::class, PoP_Module_Processor_DateRangeComponentFilterInputs::MODULE_FILTERINPUT_CUSTOMPOSTDATES],
                [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::MODULE_FILTERINPUT_ORDERPOST],
            ],
            self::MODULE_SIMPLEFILTERINPUTCONTAINER_MYHIGHLIGHTS => [
                [PoP_Module_Processor_MultiSelectFilterInputs::class, PoP_Module_Processor_MultiSelectFilterInputs::MODULE_FILTERINPUT_UNMODERATEDPOSTSTATUS],
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_SEARCH],
                [PoP_Module_Processor_PostSelectableTypeaheadFilterComponents::class, PoP_Module_Processor_PostSelectableTypeaheadFilterComponents::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES],
                [PoP_Module_Processor_DateRangeComponentFilterInputs::class, PoP_Module_Processor_DateRangeComponentFilterInputs::MODULE_FILTERINPUT_CUSTOMPOSTDATES],
                [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::MODULE_FILTERINPUT_ORDERPOST],
            ],
        ];
        if ($components = \PoP\Root\App::applyFilters(
            'Highlights:SimpleFilterInners:inputmodules',
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
    //         self::MODULE_SIMPLEFILTERINPUTCONTAINER_HIGHLIGHTS => POP_FILTER_HIGHLIGHTS,
    //         self::MODULE_SIMPLEFILTERINPUTCONTAINER_AUTHORHIGHLIGHTS => POP_FILTER_AUTHORHIGHLIGHTS,
    //         self::MODULE_SIMPLEFILTERINPUTCONTAINER_MYHIGHLIGHTS => POP_FILTER_MYHIGHLIGHTS,
    //     );
    //     if ($filter = $filters[$component[1]] ?? null) {
    //         return $filter;
    //     }

    //     return parent::getFilter($component);
    // }
}



