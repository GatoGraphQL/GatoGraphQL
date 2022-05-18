<?php

class PoP_Locations_Module_Processor_CustomSimpleFilterInners extends PoP_Module_Processor_SimpleFilterInnersBase
{
    public final const MODULE_SIMPLEFILTERINPUTCONTAINER_LOCATIONS = 'simplefilterinputcontainer-locations';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SIMPLEFILTERINPUTCONTAINER_LOCATIONS],
        );
    }

    protected function getInputSubmodules(array $module)
    {
        $ret = parent::getInputSubmodules($module);

        $inputmodules = [
            self::MODULE_SIMPLEFILTERINPUTCONTAINER_LOCATIONS => [
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_SEARCH],
            ],
        ];
        if ($modules = \PoP\Root\App::applyFilters(
            'Locations:FilterInnerComponentProcessor:inputmodules',
            $inputmodules[$module[1]],
            $module
        )) {
            $ret = array_merge(
                $ret,
                $modules
            );
        }
        return $ret;
    }

    // public function getFilter(array $module)
    // {
    //     $filters = array(
    //         self::MODULE_SIMPLEFILTERINPUTCONTAINER_LOCATIONS => POP_FILTER_LOCATIONS,
    //     );
    //     if ($filter = $filters[$module[1]] ?? null) {
    //         return $filter;
    //     }

    //     return parent::getFilter($module);
    // }
}



