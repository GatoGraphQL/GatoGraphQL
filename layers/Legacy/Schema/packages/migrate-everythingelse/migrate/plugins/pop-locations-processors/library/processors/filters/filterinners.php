<?php

class PoP_Locations_Module_Processor_CustomFilterInners extends PoP_Module_Processor_FilterInnersBase
{
    public final const MODULE_FILTERINPUTCONTAINER_LOCATIONS = 'filterinputcontainer-locations';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUTCONTAINER_LOCATIONS],
        );
    }

    protected function getInputSubmodules(array $component)
    {
        $ret = parent::getInputSubmodules($component);

        $inputmodules = [
            self::MODULE_FILTERINPUTCONTAINER_LOCATIONS => [
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_SEARCH],
            ],
        ];
        if ($components = \PoP\Root\App::applyFilters(
            'Locations:FilterInnerComponentProcessor:inputmodules',
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
    //         self::MODULE_FILTERINPUTCONTAINER_LOCATIONS => POP_FILTER_LOCATIONS,
    //     );
    //     if ($filter = $filters[$component[1]] ?? null) {
    //         return $filter;
    //     }

    //     return parent::getFilter($component);
    // }
}



