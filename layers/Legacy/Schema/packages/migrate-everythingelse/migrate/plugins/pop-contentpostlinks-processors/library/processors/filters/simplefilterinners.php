<?php

class PoP_ContentPostLinks_Module_Processor_CustomSimpleFilterInners extends PoP_Module_Processor_SimpleFilterInnersBase
{
    public final const COMPONENT_SIMPLEFILTERINPUTCONTAINER_LINKS = 'simplefilterinputcontainer-links';
    public final const COMPONENT_SIMPLEFILTERINPUTCONTAINER_AUTHORLINKS = 'simplefilterinputcontainer-authorlinks';
    public final const COMPONENT_SIMPLEFILTERINPUTCONTAINER_TAGLINKS = 'simplefilterinputcontainer-taglinks';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_LINKS],
            [self::class, self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_AUTHORLINKS],
            [self::class, self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_TAGLINKS],
        );
    }

    protected function getInputSubcomponents(array $component)
    {
        $ret = parent::getInputSubcomponents($component);

        $inputComponents = [
            self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_LINKS => [
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::COMPONENT_FILTERINPUT_SEARCH],
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::COMPONENT_FILTERINPUT_HASHTAGS],
                [PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::class, PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES],
                [PoP_Module_Processor_DateRangeComponentFilterInputs::class, PoP_Module_Processor_DateRangeComponentFilterInputs::COMPONENT_FILTERINPUT_CUSTOMPOSTDATES],
                [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::COMPONENT_FILTERINPUT_ORDERPOST],
            ],
            self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_AUTHORLINKS => [
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::COMPONENT_FILTERINPUT_SEARCH],
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::COMPONENT_FILTERINPUT_HASHTAGS],
                [PoP_Module_Processor_DateRangeComponentFilterInputs::class, PoP_Module_Processor_DateRangeComponentFilterInputs::COMPONENT_FILTERINPUT_CUSTOMPOSTDATES],
                [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::COMPONENT_FILTERINPUT_ORDERPOST],
            ],
            self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_TAGLINKS => [
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::COMPONENT_FILTERINPUT_SEARCH],
                [PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::class, PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES],
                [PoP_Module_Processor_DateRangeComponentFilterInputs::class, PoP_Module_Processor_DateRangeComponentFilterInputs::COMPONENT_FILTERINPUT_CUSTOMPOSTDATES],
                [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::COMPONENT_FILTERINPUT_ORDERPOST],
            ],
        ];
        // Add the link access filter
        if (($inputComponents[$component[1]] ?? null) && PoP_ApplicationProcessors_Utils::addLinkAccesstype()) {

            array_splice(
                $ret,
                array_search(
                    [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::COMPONENT_FILTERINPUT_SEARCH],
                    $ret
                ),
                0,
                [
                    [PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::class, PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::COMPONENT_FILTERINPUT_LINKACCESS],
                ]
            );
        }
        if ($components = \PoP\Root\App::applyFilters(
            'Links:FilterInnerComponentProcessor:inputComponents',
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
    //         self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_LINKS => POP_FILTER_LINKS,
    //         self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_AUTHORLINKS => POP_FILTER_AUTHORLINKS,
    //     );
    //     if ($filter = $filters[$component[1]] ?? null) {
    //         return $filter;
    //     }

    //     return parent::getFilter($component);
    // }
}



