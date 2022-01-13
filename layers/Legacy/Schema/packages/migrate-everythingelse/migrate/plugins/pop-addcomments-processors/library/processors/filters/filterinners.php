<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_Module_Processor_CommentFilterInners extends PoP_Module_Processor_FilterInnersBase
{
    public const MODULE_FILTERINPUTCONTAINER_COMMENTS = 'filterinputcontainer-comments';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUTCONTAINER_COMMENTS],
        );
    }

    protected function getInputSubmodules(array $module)
    {
        $ret = parent::getInputSubmodules($module);

        $inputmodules = [
            self::MODULE_FILTERINPUTCONTAINER_COMMENTS => [
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_SEARCH],
                [PoP_Module_Processor_FormComponentGroups::class, PoP_Module_Processor_FormComponentGroups::MODULE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_PROFILES],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_ORDERCOMMENT],
            ],
        ];
        if ($modules = \PoP\Root\App::getHookManager()->applyFilters(
            'Comments:FilterInnerModuleProcessor:inputmodules',
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
    //     switch ($module[1]) {
    //         case self::MODULE_FILTERINPUTCONTAINER_COMMENTS:
    //             return POP_FILTER_COMMENTS;
    //     }

    //     return parent::getFilter($module);
    // }
}



