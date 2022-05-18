<?php

class PoP_LocationPosts_Module_Processor_CustomFilterInners extends PoP_Module_Processor_FilterInnersBase
{
    public final const MODULE_FILTERINPUTCONTAINER_LOCATIONPOSTS = 'filterinputcontainer-locationposts';
    public final const MODULE_FILTERINPUTCONTAINER_AUTHORLOCATIONPOSTS = 'filterinputcontainer-authorlocationposts';
    public final const MODULE_FILTERINPUTCONTAINER_TAGLOCATIONPOSTS = 'filterinputcontainer-taglocationposts';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUTCONTAINER_LOCATIONPOSTS],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_AUTHORLOCATIONPOSTS],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_TAGLOCATIONPOSTS],
        );
    }

    protected function getInputSubmodules(array $component)
    {
        $ret = parent::getInputSubmodules($component);

        $inputmodules = [
            self::MODULE_FILTERINPUTCONTAINER_LOCATIONPOSTS => [
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_SEARCH],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_HASHTAGS],
                [PoP_Module_Processor_FormComponentGroups::class, PoP_Module_Processor_FormComponentGroups::MODULE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_PROFILES],
                [GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::class, GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::MODULE_FILTERINPUTGROUP_POSTDATES],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_ORDERPOST],
            ],
            self::MODULE_FILTERINPUTCONTAINER_AUTHORLOCATIONPOSTS => [
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_SEARCH],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_HASHTAGS],
                [GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::class, GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::MODULE_FILTERINPUTGROUP_POSTDATES],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_ORDERPOST],
            ],
            self::MODULE_FILTERINPUTCONTAINER_TAGLOCATIONPOSTS => [
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_SEARCH],
                [PoP_Module_Processor_FormComponentGroups::class, PoP_Module_Processor_FormComponentGroups::MODULE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_PROFILES],
                [GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::class, GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::MODULE_FILTERINPUTGROUP_POSTDATES],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_ORDERPOST],
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
    //         self::MODULE_FILTERINPUTCONTAINER_LOCATIONPOSTS => POP_FILTER_LOCATIONPOSTS,
    //         self::MODULE_FILTERINPUTCONTAINER_AUTHORLOCATIONPOSTS => POP_FILTER_AUTHORLOCATIONPOSTS,
    //         self::MODULE_FILTERINPUTCONTAINER_TAGLOCATIONPOSTS => POP_FILTER_TAGLOCATIONPOSTS,
    //     );
    //     if ($filter = $filters[$component[1]] ?? null) {
    //         return $filter;
    //     }

    //     return parent::getFilter($component);
    // }
}



