<?php

class PoPVP_Module_Processor_CustomSimpleFilterInners extends PoP_Module_Processor_SimpleFilterInnersBase
{
    public final const MODULE_SIMPLEFILTERINPUTCONTAINER_STANCES = 'simplefilterinnet-stances';
    public final const MODULE_SIMPLEFILTERINPUTCONTAINER_AUTHORSTANCES = 'simplefilterinnet-authorstance';
    public final const MODULE_SIMPLEFILTERINPUTCONTAINER_MYSTANCES = 'simplefilterinnet-mystances';
    public final const MODULE_SIMPLEFILTERINPUTCONTAINER_STANCES_AUTHORROLE = 'simplefilterinnet-stances-authorrole';
    public final const MODULE_SIMPLEFILTERINPUTCONTAINER_STANCES_STANCE = 'simplefilterinnet-stances-stance';
    public final const MODULE_SIMPLEFILTERINPUTCONTAINER_AUTHORSTANCES_STANCE = 'simplefilterinnet-authorstances-stance';
    public final const MODULE_SIMPLEFILTERINPUTCONTAINER_STANCES_GENERALSTANCE = 'simplefilterinnet-stances-generalstance';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SIMPLEFILTERINPUTCONTAINER_STANCES],
            [self::class, self::MODULE_SIMPLEFILTERINPUTCONTAINER_AUTHORSTANCES],
            [self::class, self::MODULE_SIMPLEFILTERINPUTCONTAINER_MYSTANCES],
            [self::class, self::MODULE_SIMPLEFILTERINPUTCONTAINER_STANCES_AUTHORROLE],
            [self::class, self::MODULE_SIMPLEFILTERINPUTCONTAINER_STANCES_STANCE],
            [self::class, self::MODULE_SIMPLEFILTERINPUTCONTAINER_AUTHORSTANCES_STANCE],
            [self::class, self::MODULE_SIMPLEFILTERINPUTCONTAINER_STANCES_GENERALSTANCE],
        );
    }

    protected function getInputSubmodules(array $componentVariation)
    {
        $ret = parent::getInputSubmodules($componentVariation);

        $inputmodules = [
            self::MODULE_SIMPLEFILTERINPUTCONTAINER_STANCES => [
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_SEARCH],
                [UserStance_Module_Processor_MultiSelectFilterInputs::class, UserStance_Module_Processor_MultiSelectFilterInputs::MODULE_FILTERINPUT_STANCE_MULTISELECT],
                [PoP_Module_Processor_PostSelectableTypeaheadFilterComponents::class, PoP_Module_Processor_PostSelectableTypeaheadFilterComponents::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES],
                [PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::class, PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES],
                [PoP_Module_Processor_DateRangeComponentFilterInputs::class, PoP_Module_Processor_DateRangeComponentFilterInputs::MODULE_FILTERINPUT_CUSTOMPOSTDATES],
                [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::MODULE_FILTERINPUT_ORDERPOST],
            ],
            self::MODULE_SIMPLEFILTERINPUTCONTAINER_AUTHORSTANCES => [
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_SEARCH],
                [UserStance_Module_Processor_MultiSelectFilterInputs::class, UserStance_Module_Processor_MultiSelectFilterInputs::MODULE_FILTERINPUT_STANCE_MULTISELECT],
                [PoP_Module_Processor_PostSelectableTypeaheadFilterComponents::class, PoP_Module_Processor_PostSelectableTypeaheadFilterComponents::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES],
                [PoP_Module_Processor_DateRangeComponentFilterInputs::class, PoP_Module_Processor_DateRangeComponentFilterInputs::MODULE_FILTERINPUT_CUSTOMPOSTDATES],
                [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::MODULE_FILTERINPUT_ORDERPOST],
            ],
            self::MODULE_SIMPLEFILTERINPUTCONTAINER_MYSTANCES => [
                [PoP_Module_Processor_MultiSelectFilterInputs::class, PoP_Module_Processor_MultiSelectFilterInputs::MODULE_FILTERINPUT_UNMODERATEDPOSTSTATUS],
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_SEARCH],
                [UserStance_Module_Processor_MultiSelectFilterInputs::class, UserStance_Module_Processor_MultiSelectFilterInputs::MODULE_FILTERINPUT_STANCE_MULTISELECT],
                [PoP_Module_Processor_PostSelectableTypeaheadFilterComponents::class, PoP_Module_Processor_PostSelectableTypeaheadFilterComponents::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES],
                [PoP_Module_Processor_DateRangeComponentFilterInputs::class, PoP_Module_Processor_DateRangeComponentFilterInputs::MODULE_FILTERINPUT_CUSTOMPOSTDATES],
                [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::MODULE_FILTERINPUT_ORDERPOST],
            ],
            self::MODULE_SIMPLEFILTERINPUTCONTAINER_STANCES_AUTHORROLE => [
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_SEARCH],
                [UserStance_Module_Processor_MultiSelectFilterInputs::class, UserStance_Module_Processor_MultiSelectFilterInputs::MODULE_FILTERINPUT_STANCE_MULTISELECT],
                [PoP_Module_Processor_PostSelectableTypeaheadFilterComponents::class, PoP_Module_Processor_PostSelectableTypeaheadFilterComponents::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES],
                [PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::class, PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES],
                [PoP_Module_Processor_DateRangeComponentFilterInputs::class, PoP_Module_Processor_DateRangeComponentFilterInputs::MODULE_FILTERINPUT_CUSTOMPOSTDATES],
                [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::MODULE_FILTERINPUT_ORDERPOST],
            ],
            self::MODULE_SIMPLEFILTERINPUTCONTAINER_STANCES_STANCE => [
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_SEARCH],
                [PoP_Module_Processor_PostSelectableTypeaheadFilterComponents::class, PoP_Module_Processor_PostSelectableTypeaheadFilterComponents::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES],
                [PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::class, PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES],
                [PoP_Module_Processor_DateRangeComponentFilterInputs::class, PoP_Module_Processor_DateRangeComponentFilterInputs::MODULE_FILTERINPUT_CUSTOMPOSTDATES],
                [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::MODULE_FILTERINPUT_ORDERPOST],
            ],
            self::MODULE_SIMPLEFILTERINPUTCONTAINER_AUTHORSTANCES_STANCE => [
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_SEARCH],
                [PoP_Module_Processor_PostSelectableTypeaheadFilterComponents::class, PoP_Module_Processor_PostSelectableTypeaheadFilterComponents::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES],
                [PoP_Module_Processor_DateRangeComponentFilterInputs::class, PoP_Module_Processor_DateRangeComponentFilterInputs::MODULE_FILTERINPUT_CUSTOMPOSTDATES],
                [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::MODULE_FILTERINPUT_ORDERPOST],
            ],
            self::MODULE_SIMPLEFILTERINPUTCONTAINER_STANCES_GENERALSTANCE => [
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_SEARCH],
                [PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::class, PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES],
                [PoP_Module_Processor_DateRangeComponentFilterInputs::class, PoP_Module_Processor_DateRangeComponentFilterInputs::MODULE_FILTERINPUT_CUSTOMPOSTDATES],
                [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::MODULE_FILTERINPUT_ORDERPOST],
            ],
        ];
        if ($componentVariations = \PoP\Root\App::applyFilters(
            'Stances:SimpleFilterInners:inputmodules',
            $inputmodules[$componentVariation[1]],
            $componentVariation
        )) {
            $ret = array_merge(
                $ret,
                $componentVariations
            );
        }
        return $ret;
    }

    // public function getFilter(array $componentVariation)
    // {
    //     $filters = array(
    //         self::MODULE_SIMPLEFILTERINPUTCONTAINER_STANCES => POP_FILTER_STANCES,
    //         self::MODULE_SIMPLEFILTERINPUTCONTAINER_AUTHORSTANCES => POP_FILTER_AUTHORSTANCES,
    //         self::MODULE_SIMPLEFILTERINPUTCONTAINER_MYSTANCES => POP_FILTER_MYSTANCES,
    //         self::MODULE_SIMPLEFILTERINPUTCONTAINER_STANCES_AUTHORROLE => POP_FILTER_STANCES_AUTHORROLE,
    //         self::MODULE_SIMPLEFILTERINPUTCONTAINER_STANCES_STANCE => POP_FILTER_STANCES_STANCE,
    //         self::MODULE_SIMPLEFILTERINPUTCONTAINER_AUTHORSTANCES_STANCE => POP_FILTER_AUTHORSTANCES_STANCE,
    //         self::MODULE_SIMPLEFILTERINPUTCONTAINER_STANCES_GENERALSTANCE => POP_FILTER_STANCES_GENERALSTANCE,
    //     );
    //     if ($filter = $filters[$componentVariation[1]] ?? null) {
    //         return $filter;
    //     }

    //     return parent::getFilter($componentVariation);
    // }
}



