<?php

class GD_URE_Module_Processor_CustomSimpleFilterInners extends PoP_Module_Processor_SimpleFilterInnersBase
{
    public const MODULE_SIMPLEFILTERINPUTCONTAINER_MYMEMBERS = 'simplefilterinputcontainer-mymembers';
    public const MODULE_SIMPLEFILTERINPUTCONTAINER_COMMUNITIES = 'simplefilterinputcontainer-communities';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SIMPLEFILTERINPUTCONTAINER_MYMEMBERS],
            [self::class, self::MODULE_SIMPLEFILTERINPUTCONTAINER_COMMUNITIES],
        );
    }

    protected function getInputSubmodules(array $module)
    {
        $ret = parent::getInputSubmodules($module);

        $inputmodules = [
            self::MODULE_SIMPLEFILTERINPUTCONTAINER_MYMEMBERS => [
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_NAME],
                [GD_URE_Module_Processor_ProfileMultiSelectFilterInputs::class, GD_URE_Module_Processor_ProfileMultiSelectFilterInputs::MODULE_URE_FILTERINPUT_MEMBERSTATUS],
                [GD_URE_Module_Processor_ProfileMultiSelectFilterInputs::class, GD_URE_Module_Processor_ProfileMultiSelectFilterInputs::MODULE_URE_FILTERINPUT_MEMBERPRIVILEGES],
                [GD_URE_Module_Processor_ProfileMultiSelectFilterInputs::class, GD_URE_Module_Processor_ProfileMultiSelectFilterInputs::MODULE_URE_FILTERINPUT_MEMBERTAGS],
                [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::MODULE_FILTERINPUT_ORDERUSER],
            ],
            self::MODULE_SIMPLEFILTERINPUTCONTAINER_COMMUNITIES => [
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_NAME],
                [GD_URE_Module_Processor_UserSelectableTypeaheadFilterInputs::class, GD_URE_Module_Processor_UserSelectableTypeaheadFilterInputs::MODULE_URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES_USER],
                [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::MODULE_FILTERINPUT_ORDERUSER],
            ],
        ];
        if ($modules = \PoP\Root\App::applyFilters(
            'UserCommunities:SimpleFilterInners:inputmodules',
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
    //         self::MODULE_SIMPLEFILTERINPUTCONTAINER_MYMEMBERS => POP_FILTER_MYMEMBERS,
    //         self::MODULE_SIMPLEFILTERINPUTCONTAINER_COMMUNITIES => POP_FILTER_COMMUNITIES,
    //     );
    //     if ($filter = $filters[$module[1]] ?? null) {
    //         return $filter;
    //     }

    //     return parent::getFilter($module);
    // }
}



