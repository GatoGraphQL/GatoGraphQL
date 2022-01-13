<?php

class GD_URE_Module_Processor_CustomFilterInners extends PoP_Module_Processor_FilterInnersBase
{
    public const MODULE_FILTERINPUTCONTAINER_MYMEMBERS = 'filterinputcontainer-mymembers';
    public const MODULE_FILTERINPUTCONTAINER_COMMUNITIES = 'filterinputcontainer-communities';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUTCONTAINER_MYMEMBERS],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_COMMUNITIES],
        );
    }

    protected function getInputSubmodules(array $module)
    {
        $ret = parent::getInputSubmodules($module);

        $inputmodules = [
            self::MODULE_FILTERINPUTCONTAINER_MYMEMBERS => [
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_NAME],
                [GD_URE_Module_Processor_ProfileFormGroups::class, GD_URE_Module_Processor_ProfileFormGroups::MODULE_URE_FILTERINPUTGROUP_MEMBERSTATUS],
                [GD_URE_Module_Processor_ProfileFormGroups::class, GD_URE_Module_Processor_ProfileFormGroups::MODULE_URE_FILTERINPUTGROUP_MEMBERPRIVILEGES],
                [GD_URE_Module_Processor_ProfileFormGroups::class, GD_URE_Module_Processor_ProfileFormGroups::MODULE_URE_FILTERINPUTGROUP_MEMBERTAGS],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_ORDERUSER],
            ],
            self::MODULE_FILTERINPUTCONTAINER_COMMUNITIES => [
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_NAME],
                [GD_URE_Module_Processor_FormGroups::class, GD_URE_Module_Processor_FormGroups::MODULE_URE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITIES_USER],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_ORDERUSER],
            ],
        ];
        if ($modules = \PoP\Root\App::getHookManager()->applyFilters(
            'UserCommunities:FilterInnerModuleProcessor:inputmodules',
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
    //         self::MODULE_FILTERINPUTCONTAINER_MYMEMBERS => POP_FILTER_MYMEMBERS,
    //         self::MODULE_FILTERINPUTCONTAINER_COMMUNITIES => POP_FILTER_COMMUNITIES,
    //     );
    //     if ($filter = $filters[$module[1]] ?? null) {
    //         return $filter;
    //     }

    //     return parent::getFilter($module);
    // }
}



