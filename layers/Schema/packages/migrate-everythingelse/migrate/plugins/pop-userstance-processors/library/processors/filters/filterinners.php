<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class UserStance_Module_Processor_CustomFilterInners extends PoP_Module_Processor_FilterInnersBase
{
    public const MODULE_FILTERINNER_STANCES = 'filterinner-stances';
    public const MODULE_FILTERINNER_AUTHORSTANCES = 'filterinner-authorstances';
    public const MODULE_FILTERINNER_MYSTANCES = 'filterinner-mystances';
    public const MODULE_FILTERINNER_STANCES_AUTHORROLE = 'filterinner-stances-authorrole';
    public const MODULE_FILTERINNER_STANCES_STANCE = 'filterinner-stances-stance';
    public const MODULE_FILTERINNER_AUTHORSTANCES_STANCE = 'filterinner-authorstances-stance';
    public const MODULE_FILTERINNER_STANCES_GENERALSTANCE = 'filterinner-stances-generalstance';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINNER_STANCES],
            [self::class, self::MODULE_FILTERINNER_AUTHORSTANCES],
            [self::class, self::MODULE_FILTERINNER_MYSTANCES],
            [self::class, self::MODULE_FILTERINNER_STANCES_AUTHORROLE],
            [self::class, self::MODULE_FILTERINNER_STANCES_STANCE],
            [self::class, self::MODULE_FILTERINNER_AUTHORSTANCES_STANCE],
            [self::class, self::MODULE_FILTERINNER_STANCES_GENERALSTANCE],
        );
    }

    protected function getInputSubmodules(array $module)
    {
        $ret = parent::getInputSubmodules($module);

        $inputmodules = [
            self::MODULE_FILTERINNER_STANCES => [
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_SEARCH],
                [UserStance_Module_Processor_CreateUpdatePostFormInputGroups::class, UserStance_Module_Processor_CreateUpdatePostFormInputGroups::MODULE_FILTERINPUTGROUP_BUTTONGROUP_STANCE],
                [PoP_RelatedPosts_Module_Processor_FormComponentGroups::class, PoP_RelatedPosts_Module_Processor_FormComponentGroups::MODULE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_REFERENCES],
                [PoP_Module_Processor_FormComponentGroups::class, PoP_Module_Processor_FormComponentGroups::MODULE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_PROFILES],
                [GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::class, GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::MODULE_FILTERINPUTGROUP_POSTDATES],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_ORDERPOST],
            ],
            self::MODULE_FILTERINNER_AUTHORSTANCES => [
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_SEARCH],
                [UserStance_Module_Processor_CreateUpdatePostFormInputGroups::class, UserStance_Module_Processor_CreateUpdatePostFormInputGroups::MODULE_FILTERINPUTGROUP_BUTTONGROUP_STANCE],
                [PoP_RelatedPosts_Module_Processor_FormComponentGroups::class, PoP_RelatedPosts_Module_Processor_FormComponentGroups::MODULE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_REFERENCES],
                [GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::class, GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::MODULE_FILTERINPUTGROUP_POSTDATES],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_ORDERPOST],
            ],
            self::MODULE_FILTERINNER_MYSTANCES => [
                [GD_Core_Bootstrap_Module_Processor_FormInputGroups::class, GD_Core_Bootstrap_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_UNMODERATEDPOSTSTATUS],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_SEARCH],
                [UserStance_Module_Processor_CreateUpdatePostFormInputGroups::class, UserStance_Module_Processor_CreateUpdatePostFormInputGroups::MODULE_FILTERINPUTGROUP_BUTTONGROUP_STANCE],
                [PoP_RelatedPosts_Module_Processor_FormComponentGroups::class, PoP_RelatedPosts_Module_Processor_FormComponentGroups::MODULE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_REFERENCES],
                [GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::class, GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::MODULE_FILTERINPUTGROUP_POSTDATES],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_ORDERPOST],
            ],
            self::MODULE_FILTERINNER_STANCES_AUTHORROLE => [
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_SEARCH],
                [UserStance_Module_Processor_CreateUpdatePostFormInputGroups::class, UserStance_Module_Processor_CreateUpdatePostFormInputGroups::MODULE_FILTERINPUTGROUP_BUTTONGROUP_STANCE],
                [PoP_RelatedPosts_Module_Processor_FormComponentGroups::class, PoP_RelatedPosts_Module_Processor_FormComponentGroups::MODULE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_REFERENCES],
                [PoP_Module_Processor_FormComponentGroups::class, PoP_Module_Processor_FormComponentGroups::MODULE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_PROFILES],
                [GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::class, GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::MODULE_FILTERINPUTGROUP_POSTDATES],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_ORDERPOST],
            ],
            self::MODULE_FILTERINNER_STANCES_STANCE => [
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_SEARCH],
                [PoP_RelatedPosts_Module_Processor_FormComponentGroups::class, PoP_RelatedPosts_Module_Processor_FormComponentGroups::MODULE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_REFERENCES],
                [PoP_Module_Processor_FormComponentGroups::class, PoP_Module_Processor_FormComponentGroups::MODULE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_PROFILES],
                [GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::class, GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::MODULE_FILTERINPUTGROUP_POSTDATES],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_ORDERPOST],
            ],
            self::MODULE_FILTERINNER_AUTHORSTANCES_STANCE => [
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_SEARCH],
                [PoP_RelatedPosts_Module_Processor_FormComponentGroups::class, PoP_RelatedPosts_Module_Processor_FormComponentGroups::MODULE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_REFERENCES],
                [GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::class, GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::MODULE_FILTERINPUTGROUP_POSTDATES],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_ORDERPOST],
            ],
            self::MODULE_FILTERINNER_STANCES_GENERALSTANCE => [
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_SEARCH],
                [PoP_Module_Processor_FormComponentGroups::class, PoP_Module_Processor_FormComponentGroups::MODULE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_PROFILES],
                [GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::class, GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::MODULE_FILTERINPUTGROUP_POSTDATES],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_ORDERPOST],
            ],
        ];
        if ($modules = HooksAPIFacade::getInstance()->applyFilters(
            'Stances:FilterInners:inputmodules',
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
    //         self::MODULE_FILTERINNER_STANCES => POP_FILTER_STANCES,
    //         self::MODULE_FILTERINNER_AUTHORSTANCES => POP_FILTER_AUTHORSTANCES,
    //         self::MODULE_FILTERINNER_MYSTANCES => POP_FILTER_MYSTANCES,
    //         self::MODULE_FILTERINNER_STANCES_AUTHORROLE => POP_FILTER_STANCES_AUTHORROLE,
    //         self::MODULE_FILTERINNER_STANCES_STANCE => POP_FILTER_STANCES_STANCE,
    //         self::MODULE_FILTERINNER_AUTHORSTANCES_STANCE => POP_FILTER_AUTHORSTANCES_STANCE,
    //         self::MODULE_FILTERINNER_STANCES_GENERALSTANCE => POP_FILTER_STANCES_GENERALSTANCE,
    //     );
    //     if ($filter = $filters[$module[1]]) {
    //         return $filter;
    //     }
        
    //     return parent::getFilter($module);
    // }
}



