<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class PoP_AddHighlights_Module_Processor_CustomFilterInners extends PoP_Module_Processor_FilterInnersBase
{
    public const MODULE_FILTERINNER_HIGHLIGHTS = 'filterinner-highlights';
    public const MODULE_FILTERINNER_AUTHORHIGHLIGHTS = 'filterinner-authorhighlights';
    public const MODULE_FILTERINNER_MYHIGHLIGHTS = 'filterinner-myhighlights';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINNER_HIGHLIGHTS],
            [self::class, self::MODULE_FILTERINNER_AUTHORHIGHLIGHTS],
            [self::class, self::MODULE_FILTERINNER_MYHIGHLIGHTS],
        );
    }

    protected function getInputSubmodules(array $module)
    {
        $ret = parent::getInputSubmodules($module);

        $inputmodules = [
            self::MODULE_FILTERINNER_HIGHLIGHTS => [
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_SEARCH],
                [PoP_Module_Processor_FormComponentGroups::class, PoP_Module_Processor_FormComponentGroups::MODULE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_PROFILES],
                [GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::class, GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::MODULE_FILTERINPUTGROUP_POSTDATES],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_ORDERPOST],
            ],
            self::MODULE_FILTERINNER_AUTHORHIGHLIGHTS => [
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_SEARCH],
                [PoP_RelatedPosts_Module_Processor_FormComponentGroups::class, PoP_RelatedPosts_Module_Processor_FormComponentGroups::MODULE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_REFERENCES],
                [GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::class, GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::MODULE_FILTERINPUTGROUP_POSTDATES],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_ORDERPOST],
            ],
            self::MODULE_FILTERINNER_MYHIGHLIGHTS => [
                [GD_Core_Bootstrap_Module_Processor_FormInputGroups::class, GD_Core_Bootstrap_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_UNMODERATEDPOSTSTATUS],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_SEARCH],
                [PoP_RelatedPosts_Module_Processor_FormComponentGroups::class, PoP_RelatedPosts_Module_Processor_FormComponentGroups::MODULE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_REFERENCES],
                [GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::class, GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::MODULE_FILTERINPUTGROUP_POSTDATES],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_ORDERPOST],
            ],
        ];
        if ($modules = HooksAPIFacade::getInstance()->applyFilters(
            'Highlights:FilterInners:inputmodules',
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
    //         self::MODULE_FILTERINNER_HIGHLIGHTS => POP_FILTER_HIGHLIGHTS,
    //         self::MODULE_FILTERINNER_AUTHORHIGHLIGHTS => POP_FILTER_AUTHORHIGHLIGHTS,
    //         self::MODULE_FILTERINNER_MYHIGHLIGHTS => POP_FILTER_MYHIGHLIGHTS,
    //     );
    //     if ($filter = $filters[$module[1]]) {
    //         return $filter;
    //     }
        
    //     return parent::getFilter($module);
    // }
}



