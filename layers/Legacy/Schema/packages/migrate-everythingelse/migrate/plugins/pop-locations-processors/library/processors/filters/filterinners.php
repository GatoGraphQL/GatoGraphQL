<?php

class PoP_Locations_Module_Processor_CustomFilterInners extends PoP_Module_Processor_FilterInnersBase
{
    public final const MODULE_FILTERINPUTCONTAINER_LOCATIONS = 'filterinputcontainer-locations';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUTCONTAINER_LOCATIONS],
        );
    }

    protected function getInputSubmodules(array $componentVariation)
    {
        $ret = parent::getInputSubmodules($componentVariation);

        $inputmodules = [
            self::MODULE_FILTERINPUTCONTAINER_LOCATIONS => [
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_SEARCH],
            ],
        ];
        if ($componentVariations = \PoP\Root\App::applyFilters(
            'Locations:FilterInnerComponentProcessor:inputmodules',
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
    //         self::MODULE_FILTERINPUTCONTAINER_LOCATIONS => POP_FILTER_LOCATIONS,
    //     );
    //     if ($filter = $filters[$componentVariation[1]] ?? null) {
    //         return $filter;
    //     }

    //     return parent::getFilter($componentVariation);
    // }
}



