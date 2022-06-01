<?php

class PoP_ContentPostLinksCreation_Module_Processor_CustomSimpleFilterInners extends PoP_Module_Processor_SimpleFilterInnersBase
{
    public final const COMPONENT_SIMPLEFILTERINPUTCONTAINER_MYLINKS = 'simplefilterinputcontainer-mylinks';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_MYLINKS],
        );
    }

    protected function getInputSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getInputSubcomponents($component);

        $inputComponents = [
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
        if (($inputComponents[$component->name] ?? null) && PoP_ApplicationProcessors_Utils::addLinkAccesstype()) {
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
            'Links:SimpleFilterInners:inputComponents',
            $inputComponents[$component->name],
            $component
        )) {
            $ret = array_merge(
                $ret,
                $components
            );
        }
        return $ret;
    }

    // public function getFilter(\PoP\ComponentModel\Component\Component $component)
    // {
    //     $filters = array(
    //         self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_MYLINKS => POP_FILTER_MYLINKS,
    //     );
    //     if ($filter = $filters[$component->name] ?? null) {
    //         return $filter;
    //     }

    //     return parent::getFilter($component);
    // }
}



