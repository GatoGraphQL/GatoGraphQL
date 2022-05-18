<?php

class PoP_Locations_Module_Processor_CustomSimpleFilterInners extends PoP_Module_Processor_SimpleFilterInnersBase
{
    public final const MODULE_SIMPLEFILTERINPUTCONTAINER_LOCATIONS = 'simplefilterinputcontainer-locations';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SIMPLEFILTERINPUTCONTAINER_LOCATIONS],
        );
    }

    protected function getInputSubmodules(array $componentVariation)
    {
        $ret = parent::getInputSubmodules($componentVariation);

        $inputmodules = [
            self::MODULE_SIMPLEFILTERINPUTCONTAINER_LOCATIONS => [
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_SEARCH],
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
    //         self::MODULE_SIMPLEFILTERINPUTCONTAINER_LOCATIONS => POP_FILTER_LOCATIONS,
    //     );
    //     if ($filter = $filters[$componentVariation[1]] ?? null) {
    //         return $filter;
    //     }

    //     return parent::getFilter($componentVariation);
    // }
}



