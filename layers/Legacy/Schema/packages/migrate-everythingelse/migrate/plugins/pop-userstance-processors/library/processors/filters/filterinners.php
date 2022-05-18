<?php

class UserStance_Module_Processor_CustomFilterInners extends PoP_Module_Processor_FilterInnersBase
{
    public final const MODULE_FILTERINPUTCONTAINER_STANCES = 'filterinputcontainer-stances';
    public final const MODULE_FILTERINPUTCONTAINER_AUTHORSTANCES = 'filterinputcontainer-authorstances';
    public final const MODULE_FILTERINPUTCONTAINER_MYSTANCES = 'filterinputcontainer-mystances';
    public final const MODULE_FILTERINPUTCONTAINER_STANCES_AUTHORROLE = 'filterinputcontainer-stances-authorrole';
    public final const MODULE_FILTERINPUTCONTAINER_STANCES_STANCE = 'filterinputcontainer-stances-stance';
    public final const MODULE_FILTERINPUTCONTAINER_AUTHORSTANCES_STANCE = 'filterinputcontainer-authorstances-stance';
    public final const MODULE_FILTERINPUTCONTAINER_STANCES_GENERALSTANCE = 'filterinputcontainer-stances-generalstance';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_STANCES],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_AUTHORSTANCES],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_MYSTANCES],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_STANCES_AUTHORROLE],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_STANCES_STANCE],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_AUTHORSTANCES_STANCE],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_STANCES_GENERALSTANCE],
        );
    }

    protected function getInputSubmodules(array $component)
    {
        $ret = parent::getInputSubmodules($component);

        $inputmodules = [
            self::COMPONENT_FILTERINPUTCONTAINER_STANCES => [
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::COMPONENT_FILTERINPUTGROUP_SEARCH],
                [UserStance_Module_Processor_CreateUpdatePostFormInputGroups::class, UserStance_Module_Processor_CreateUpdatePostFormInputGroups::COMPONENT_FILTERINPUTGROUP_BUTTONGROUP_STANCE],
                [PoP_RelatedPosts_Module_Processor_FormComponentGroups::class, PoP_RelatedPosts_Module_Processor_FormComponentGroups::COMPONENT_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_REFERENCES],
                [PoP_Module_Processor_FormComponentGroups::class, PoP_Module_Processor_FormComponentGroups::COMPONENT_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_PROFILES],
                [GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::class, GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::COMPONENT_FILTERINPUTGROUP_POSTDATES],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::COMPONENT_FILTERINPUTGROUP_ORDERPOST],
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_AUTHORSTANCES => [
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::COMPONENT_FILTERINPUTGROUP_SEARCH],
                [UserStance_Module_Processor_CreateUpdatePostFormInputGroups::class, UserStance_Module_Processor_CreateUpdatePostFormInputGroups::COMPONENT_FILTERINPUTGROUP_BUTTONGROUP_STANCE],
                [PoP_RelatedPosts_Module_Processor_FormComponentGroups::class, PoP_RelatedPosts_Module_Processor_FormComponentGroups::COMPONENT_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_REFERENCES],
                [GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::class, GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::COMPONENT_FILTERINPUTGROUP_POSTDATES],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::COMPONENT_FILTERINPUTGROUP_ORDERPOST],
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_MYSTANCES => [
                [GD_Core_Bootstrap_Module_Processor_FormInputGroups::class, GD_Core_Bootstrap_Module_Processor_FormInputGroups::COMPONENT_FILTERINPUTGROUP_UNMODERATEDPOSTSTATUS],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::COMPONENT_FILTERINPUTGROUP_SEARCH],
                [UserStance_Module_Processor_CreateUpdatePostFormInputGroups::class, UserStance_Module_Processor_CreateUpdatePostFormInputGroups::COMPONENT_FILTERINPUTGROUP_BUTTONGROUP_STANCE],
                [PoP_RelatedPosts_Module_Processor_FormComponentGroups::class, PoP_RelatedPosts_Module_Processor_FormComponentGroups::COMPONENT_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_REFERENCES],
                [GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::class, GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::COMPONENT_FILTERINPUTGROUP_POSTDATES],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::COMPONENT_FILTERINPUTGROUP_ORDERPOST],
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_STANCES_AUTHORROLE => [
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::COMPONENT_FILTERINPUTGROUP_SEARCH],
                [UserStance_Module_Processor_CreateUpdatePostFormInputGroups::class, UserStance_Module_Processor_CreateUpdatePostFormInputGroups::COMPONENT_FILTERINPUTGROUP_BUTTONGROUP_STANCE],
                [PoP_RelatedPosts_Module_Processor_FormComponentGroups::class, PoP_RelatedPosts_Module_Processor_FormComponentGroups::COMPONENT_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_REFERENCES],
                [PoP_Module_Processor_FormComponentGroups::class, PoP_Module_Processor_FormComponentGroups::COMPONENT_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_PROFILES],
                [GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::class, GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::COMPONENT_FILTERINPUTGROUP_POSTDATES],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::COMPONENT_FILTERINPUTGROUP_ORDERPOST],
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_STANCES_STANCE => [
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::COMPONENT_FILTERINPUTGROUP_SEARCH],
                [PoP_RelatedPosts_Module_Processor_FormComponentGroups::class, PoP_RelatedPosts_Module_Processor_FormComponentGroups::COMPONENT_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_REFERENCES],
                [PoP_Module_Processor_FormComponentGroups::class, PoP_Module_Processor_FormComponentGroups::COMPONENT_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_PROFILES],
                [GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::class, GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::COMPONENT_FILTERINPUTGROUP_POSTDATES],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::COMPONENT_FILTERINPUTGROUP_ORDERPOST],
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_AUTHORSTANCES_STANCE => [
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::COMPONENT_FILTERINPUTGROUP_SEARCH],
                [PoP_RelatedPosts_Module_Processor_FormComponentGroups::class, PoP_RelatedPosts_Module_Processor_FormComponentGroups::COMPONENT_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_REFERENCES],
                [GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::class, GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::COMPONENT_FILTERINPUTGROUP_POSTDATES],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::COMPONENT_FILTERINPUTGROUP_ORDERPOST],
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_STANCES_GENERALSTANCE => [
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::COMPONENT_FILTERINPUTGROUP_SEARCH],
                [PoP_Module_Processor_FormComponentGroups::class, PoP_Module_Processor_FormComponentGroups::COMPONENT_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_PROFILES],
                [GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::class, GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::COMPONENT_FILTERINPUTGROUP_POSTDATES],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::COMPONENT_FILTERINPUTGROUP_ORDERPOST],
            ],
        ];
        if ($components = \PoP\Root\App::applyFilters(
            'Stances:FilterInnerComponentProcessor:inputmodules',
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
    //         self::COMPONENT_FILTERINPUTCONTAINER_STANCES => POP_FILTER_STANCES,
    //         self::COMPONENT_FILTERINPUTCONTAINER_AUTHORSTANCES => POP_FILTER_AUTHORSTANCES,
    //         self::COMPONENT_FILTERINPUTCONTAINER_MYSTANCES => POP_FILTER_MYSTANCES,
    //         self::COMPONENT_FILTERINPUTCONTAINER_STANCES_AUTHORROLE => POP_FILTER_STANCES_AUTHORROLE,
    //         self::COMPONENT_FILTERINPUTCONTAINER_STANCES_STANCE => POP_FILTER_STANCES_STANCE,
    //         self::COMPONENT_FILTERINPUTCONTAINER_AUTHORSTANCES_STANCE => POP_FILTER_AUTHORSTANCES_STANCE,
    //         self::COMPONENT_FILTERINPUTCONTAINER_STANCES_GENERALSTANCE => POP_FILTER_STANCES_GENERALSTANCE,
    //     );
    //     if ($filter = $filters[$component[1]] ?? null) {
    //         return $filter;
    //     }

    //     return parent::getFilter($component);
    // }
}



