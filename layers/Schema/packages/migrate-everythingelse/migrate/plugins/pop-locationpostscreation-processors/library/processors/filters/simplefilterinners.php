<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class PoPSPEM_Module_Processor_CustomSimpleFilterInners extends PoP_Module_Processor_SimpleFilterInnersBase
{
    public const MODULE_SIMPLEFILTERINNER_MYLOCATIONPOSTS = 'simplefilterinner-mylocationposts';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SIMPLEFILTERINNER_MYLOCATIONPOSTS],
        );
    }

    protected function getInputSubmodules(array $module)
    {
        $ret = parent::getInputSubmodules($module);

        $inputmodules = [
            self::MODULE_SIMPLEFILTERINNER_MYLOCATIONPOSTS => [
                GD_CreateUpdate_Utils::moderate() ?
                    [GD_Core_Bootstrap_Module_Processor_FormInputGroups::class, GD_Core_Bootstrap_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_MODERATEDPOSTSTATUS] :
                    [GD_Core_Bootstrap_Module_Processor_FormInputGroups::class, GD_Core_Bootstrap_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_UNMODERATEDPOSTSTATUS],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_SEARCH],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_HASHTAGS],
                [GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::class, GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::MODULE_FILTERINPUTGROUP_POSTDATES],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_ORDERPOST],
            ],
        ];
        if ($modules = HooksAPIFacade::getInstance()->applyFilters(
            'Locations:SimpleFilterInners:inputmodules',
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
    //         self::MODULE_SIMPLEFILTERINNER_MYLOCATIONPOSTS => POP_FILTER_MYLOCATIONPOSTS,
    //     );
    //     if ($filter = $filters[$module[1]]) {
    //         return $filter;
    //     }
        
    //     return parent::getFilter($module);
    // }
}



