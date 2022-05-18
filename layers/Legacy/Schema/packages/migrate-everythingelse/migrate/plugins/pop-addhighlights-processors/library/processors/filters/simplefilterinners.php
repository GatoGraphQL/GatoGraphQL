<?php

class PoP_AddHighlights_Module_Processor_CustomSimpleFilterInners extends PoP_Module_Processor_SimpleFilterInnersBase
{
    public final const COMPONENT_SIMPLEFILTERINPUTCONTAINER_HIGHLIGHTS = 'simplefilterinputcontainer-highlights';
    public final const COMPONENT_SIMPLEFILTERINPUTCONTAINER_AUTHORHIGHLIGHTS = 'simplefilterinputcontainer-authorhighlights';
    public final const COMPONENT_SIMPLEFILTERINPUTCONTAINER_MYHIGHLIGHTS = 'simplefilterinputcontainer-myhighlights';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_HIGHLIGHTS],
            [self::class, self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_AUTHORHIGHLIGHTS],
            [self::class, self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_MYHIGHLIGHTS],
        );
    }

    protected function getInputSubmodules(array $component)
    {
        $ret = parent::getInputSubmodules($component);

        $inputComponents = [
            self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_HIGHLIGHTS => [
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::COMPONENT_FILTERINPUT_SEARCH],
                [PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::class, PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES],
                [PoP_Module_Processor_DateRangeComponentFilterInputs::class, PoP_Module_Processor_DateRangeComponentFilterInputs::COMPONENT_FILTERINPUT_CUSTOMPOSTDATES],
                [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::COMPONENT_FILTERINPUT_ORDERPOST],
            ],
            self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_AUTHORHIGHLIGHTS => [
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::COMPONENT_FILTERINPUT_SEARCH],
                [PoP_Module_Processor_PostSelectableTypeaheadFilterComponents::class, PoP_Module_Processor_PostSelectableTypeaheadFilterComponents::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES],
                [PoP_Module_Processor_DateRangeComponentFilterInputs::class, PoP_Module_Processor_DateRangeComponentFilterInputs::COMPONENT_FILTERINPUT_CUSTOMPOSTDATES],
                [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::COMPONENT_FILTERINPUT_ORDERPOST],
            ],
            self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_MYHIGHLIGHTS => [
                [PoP_Module_Processor_MultiSelectFilterInputs::class, PoP_Module_Processor_MultiSelectFilterInputs::COMPONENT_FILTERINPUT_UNMODERATEDPOSTSTATUS],
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::COMPONENT_FILTERINPUT_SEARCH],
                [PoP_Module_Processor_PostSelectableTypeaheadFilterComponents::class, PoP_Module_Processor_PostSelectableTypeaheadFilterComponents::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES],
                [PoP_Module_Processor_DateRangeComponentFilterInputs::class, PoP_Module_Processor_DateRangeComponentFilterInputs::COMPONENT_FILTERINPUT_CUSTOMPOSTDATES],
                [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::COMPONENT_FILTERINPUT_ORDERPOST],
            ],
        ];
        if ($components = \PoP\Root\App::applyFilters(
            'Highlights:SimpleFilterInners:inputComponents',
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
    //         self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_HIGHLIGHTS => POP_FILTER_HIGHLIGHTS,
    //         self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_AUTHORHIGHLIGHTS => POP_FILTER_AUTHORHIGHLIGHTS,
    //         self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_MYHIGHLIGHTS => POP_FILTER_MYHIGHLIGHTS,
    //     );
    //     if ($filter = $filters[$component[1]] ?? null) {
    //         return $filter;
    //     }

    //     return parent::getFilter($component);
    // }
}



