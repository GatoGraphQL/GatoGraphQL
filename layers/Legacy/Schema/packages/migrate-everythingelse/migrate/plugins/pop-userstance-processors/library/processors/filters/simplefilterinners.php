<?php

class PoPVP_Module_Processor_CustomSimpleFilterInners extends PoP_Module_Processor_SimpleFilterInnersBase
{
    public final const COMPONENT_SIMPLEFILTERINPUTCONTAINER_STANCES = 'simplefilterinnet-stances';
    public final const COMPONENT_SIMPLEFILTERINPUTCONTAINER_AUTHORSTANCES = 'simplefilterinnet-authorstance';
    public final const COMPONENT_SIMPLEFILTERINPUTCONTAINER_MYSTANCES = 'simplefilterinnet-mystances';
    public final const COMPONENT_SIMPLEFILTERINPUTCONTAINER_STANCES_AUTHORROLE = 'simplefilterinnet-stances-authorrole';
    public final const COMPONENT_SIMPLEFILTERINPUTCONTAINER_STANCES_STANCE = 'simplefilterinnet-stances-stance';
    public final const COMPONENT_SIMPLEFILTERINPUTCONTAINER_AUTHORSTANCES_STANCE = 'simplefilterinnet-authorstances-stance';
    public final const COMPONENT_SIMPLEFILTERINPUTCONTAINER_STANCES_GENERALSTANCE = 'simplefilterinnet-stances-generalstance';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_STANCES],
            [self::class, self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_AUTHORSTANCES],
            [self::class, self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_MYSTANCES],
            [self::class, self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_STANCES_AUTHORROLE],
            [self::class, self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_STANCES_STANCE],
            [self::class, self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_AUTHORSTANCES_STANCE],
            [self::class, self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_STANCES_GENERALSTANCE],
        );
    }

    protected function getInputSubmodules(array $component)
    {
        $ret = parent::getInputSubmodules($component);

        $inputComponents = [
            self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_STANCES => [
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::COMPONENT_FILTERINPUT_SEARCH],
                [UserStance_Module_Processor_MultiSelectFilterInputs::class, UserStance_Module_Processor_MultiSelectFilterInputs::COMPONENT_FILTERINPUT_STANCE_MULTISELECT],
                [PoP_Module_Processor_PostSelectableTypeaheadFilterComponents::class, PoP_Module_Processor_PostSelectableTypeaheadFilterComponents::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES],
                [PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::class, PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES],
                [PoP_Module_Processor_DateRangeComponentFilterInputs::class, PoP_Module_Processor_DateRangeComponentFilterInputs::COMPONENT_FILTERINPUT_CUSTOMPOSTDATES],
                [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::COMPONENT_FILTERINPUT_ORDERPOST],
            ],
            self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_AUTHORSTANCES => [
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::COMPONENT_FILTERINPUT_SEARCH],
                [UserStance_Module_Processor_MultiSelectFilterInputs::class, UserStance_Module_Processor_MultiSelectFilterInputs::COMPONENT_FILTERINPUT_STANCE_MULTISELECT],
                [PoP_Module_Processor_PostSelectableTypeaheadFilterComponents::class, PoP_Module_Processor_PostSelectableTypeaheadFilterComponents::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES],
                [PoP_Module_Processor_DateRangeComponentFilterInputs::class, PoP_Module_Processor_DateRangeComponentFilterInputs::COMPONENT_FILTERINPUT_CUSTOMPOSTDATES],
                [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::COMPONENT_FILTERINPUT_ORDERPOST],
            ],
            self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_MYSTANCES => [
                [PoP_Module_Processor_MultiSelectFilterInputs::class, PoP_Module_Processor_MultiSelectFilterInputs::COMPONENT_FILTERINPUT_UNMODERATEDPOSTSTATUS],
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::COMPONENT_FILTERINPUT_SEARCH],
                [UserStance_Module_Processor_MultiSelectFilterInputs::class, UserStance_Module_Processor_MultiSelectFilterInputs::COMPONENT_FILTERINPUT_STANCE_MULTISELECT],
                [PoP_Module_Processor_PostSelectableTypeaheadFilterComponents::class, PoP_Module_Processor_PostSelectableTypeaheadFilterComponents::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES],
                [PoP_Module_Processor_DateRangeComponentFilterInputs::class, PoP_Module_Processor_DateRangeComponentFilterInputs::COMPONENT_FILTERINPUT_CUSTOMPOSTDATES],
                [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::COMPONENT_FILTERINPUT_ORDERPOST],
            ],
            self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_STANCES_AUTHORROLE => [
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::COMPONENT_FILTERINPUT_SEARCH],
                [UserStance_Module_Processor_MultiSelectFilterInputs::class, UserStance_Module_Processor_MultiSelectFilterInputs::COMPONENT_FILTERINPUT_STANCE_MULTISELECT],
                [PoP_Module_Processor_PostSelectableTypeaheadFilterComponents::class, PoP_Module_Processor_PostSelectableTypeaheadFilterComponents::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES],
                [PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::class, PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES],
                [PoP_Module_Processor_DateRangeComponentFilterInputs::class, PoP_Module_Processor_DateRangeComponentFilterInputs::COMPONENT_FILTERINPUT_CUSTOMPOSTDATES],
                [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::COMPONENT_FILTERINPUT_ORDERPOST],
            ],
            self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_STANCES_STANCE => [
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::COMPONENT_FILTERINPUT_SEARCH],
                [PoP_Module_Processor_PostSelectableTypeaheadFilterComponents::class, PoP_Module_Processor_PostSelectableTypeaheadFilterComponents::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES],
                [PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::class, PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES],
                [PoP_Module_Processor_DateRangeComponentFilterInputs::class, PoP_Module_Processor_DateRangeComponentFilterInputs::COMPONENT_FILTERINPUT_CUSTOMPOSTDATES],
                [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::COMPONENT_FILTERINPUT_ORDERPOST],
            ],
            self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_AUTHORSTANCES_STANCE => [
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::COMPONENT_FILTERINPUT_SEARCH],
                [PoP_Module_Processor_PostSelectableTypeaheadFilterComponents::class, PoP_Module_Processor_PostSelectableTypeaheadFilterComponents::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES],
                [PoP_Module_Processor_DateRangeComponentFilterInputs::class, PoP_Module_Processor_DateRangeComponentFilterInputs::COMPONENT_FILTERINPUT_CUSTOMPOSTDATES],
                [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::COMPONENT_FILTERINPUT_ORDERPOST],
            ],
            self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_STANCES_GENERALSTANCE => [
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::COMPONENT_FILTERINPUT_SEARCH],
                [PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::class, PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES],
                [PoP_Module_Processor_DateRangeComponentFilterInputs::class, PoP_Module_Processor_DateRangeComponentFilterInputs::COMPONENT_FILTERINPUT_CUSTOMPOSTDATES],
                [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::COMPONENT_FILTERINPUT_ORDERPOST],
            ],
        ];
        if ($components = \PoP\Root\App::applyFilters(
            'Stances:SimpleFilterInners:inputComponents',
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
    //         self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_STANCES => POP_FILTER_STANCES,
    //         self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_AUTHORSTANCES => POP_FILTER_AUTHORSTANCES,
    //         self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_MYSTANCES => POP_FILTER_MYSTANCES,
    //         self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_STANCES_AUTHORROLE => POP_FILTER_STANCES_AUTHORROLE,
    //         self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_STANCES_STANCE => POP_FILTER_STANCES_STANCE,
    //         self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_AUTHORSTANCES_STANCE => POP_FILTER_AUTHORSTANCES_STANCE,
    //         self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_STANCES_GENERALSTANCE => POP_FILTER_STANCES_GENERALSTANCE,
    //     );
    //     if ($filter = $filters[$component[1]] ?? null) {
    //         return $filter;
    //     }

    //     return parent::getFilter($component);
    // }
}



