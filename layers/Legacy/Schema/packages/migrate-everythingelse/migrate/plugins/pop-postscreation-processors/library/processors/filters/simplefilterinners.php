<?php

class PoP_ContentPostLinksCreation_Module_Processor_CustomSimpleFilterInners extends PoP_Module_Processor_SimpleFilterInnersBase
{
    public final const MODULE_SIMPLEFILTERINPUTCONTAINER_MYLINKS = 'simplefilterinputcontainer-mylinks';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_MYLINKS],
        );
    }

    protected function getInputSubmodules(array $component)
    {
        $ret = parent::getInputSubmodules($component);

        $inputmodules = [
            self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_MYLINKS => [
                GD_CreateUpdate_Utils::moderate() ?
                    [PoP_Module_Processor_MultiSelectFilterInputs::class, PoP_Module_Processor_MultiSelectFilterInputs::COMPONENT_FILTERINPUT_MODERATEDPOSTSTATUS] :
                    [PoP_Module_Processor_MultiSelectFilterInputs::class, PoP_Module_Processor_MultiSelectFilterInputs::COMPONENT_FILTERINPUT_UNMODERATEDPOSTSTATUS],
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::COMPONENT_FILTERINPUT_SEARCH],
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::COMPONENT_FILTERINPUT_HASHTAGS],
                [PoP_Module_Processor_DateRangeComponentFilterInputs::class, PoP_Module_Processor_DateRangeComponentFilterInputs::COMPONENT_FILTERINPUT_CUSTOMPOSTDATES],
                [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::COMPONENT_FILTERINPUT_ORDERPOST],
            ],
        ];
        // Add the link access filter
        if (($inputmodules[$component[1]] ?? null) && PoP_ApplicationProcessors_Utils::addLinkAccesstype()) {
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
            'Links:SimpleFilterInners:inputmodules',
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
    //         self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_MYLINKS => POP_FILTER_MYLINKS,
    //     );
    //     if ($filter = $filters[$component[1]] ?? null) {
    //         return $filter;
    //     }

    //     return parent::getFilter($component);
    // }
}



