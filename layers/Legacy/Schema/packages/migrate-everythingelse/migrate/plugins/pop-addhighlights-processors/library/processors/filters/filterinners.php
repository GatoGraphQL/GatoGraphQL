<?php

class PoP_AddHighlights_Module_Processor_CustomFilterInners extends PoP_Module_Processor_FilterInnersBase
{
    public final const COMPONENT_FILTERINPUTCONTAINER_HIGHLIGHTS = 'filterinputcontainer-highlights';
    public final const COMPONENT_FILTERINPUTCONTAINER_AUTHORHIGHLIGHTS = 'filterinputcontainer-authorhighlights';
    public final const COMPONENT_FILTERINPUTCONTAINER_MYHIGHLIGHTS = 'filterinputcontainer-myhighlights';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_HIGHLIGHTS],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_AUTHORHIGHLIGHTS],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_MYHIGHLIGHTS],
        );
    }

    protected function getInputSubmodules(array $component)
    {
        $ret = parent::getInputSubmodules($component);

        $inputmodules = [
            self::COMPONENT_FILTERINPUTCONTAINER_HIGHLIGHTS => [
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::COMPONENT_FILTERINPUTGROUP_SEARCH],
                [PoP_Module_Processor_FormComponentGroups::class, PoP_Module_Processor_FormComponentGroups::COMPONENT_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_PROFILES],
                [GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::class, GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::COMPONENT_FILTERINPUTGROUP_POSTDATES],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::COMPONENT_FILTERINPUTGROUP_ORDERPOST],
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_AUTHORHIGHLIGHTS => [
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::COMPONENT_FILTERINPUTGROUP_SEARCH],
                [PoP_RelatedPosts_Module_Processor_FormComponentGroups::class, PoP_RelatedPosts_Module_Processor_FormComponentGroups::COMPONENT_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_REFERENCES],
                [GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::class, GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::COMPONENT_FILTERINPUTGROUP_POSTDATES],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::COMPONENT_FILTERINPUTGROUP_ORDERPOST],
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_MYHIGHLIGHTS => [
                [GD_Core_Bootstrap_Module_Processor_FormInputGroups::class, GD_Core_Bootstrap_Module_Processor_FormInputGroups::COMPONENT_FILTERINPUTGROUP_UNMODERATEDPOSTSTATUS],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::COMPONENT_FILTERINPUTGROUP_SEARCH],
                [PoP_RelatedPosts_Module_Processor_FormComponentGroups::class, PoP_RelatedPosts_Module_Processor_FormComponentGroups::COMPONENT_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_REFERENCES],
                [GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::class, GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::COMPONENT_FILTERINPUTGROUP_POSTDATES],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::COMPONENT_FILTERINPUTGROUP_ORDERPOST],
            ],
        ];
        if ($components = \PoP\Root\App::applyFilters(
            'Highlights:FilterInnerComponentProcessor:inputmodules',
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
    //         self::COMPONENT_FILTERINPUTCONTAINER_HIGHLIGHTS => POP_FILTER_HIGHLIGHTS,
    //         self::COMPONENT_FILTERINPUTCONTAINER_AUTHORHIGHLIGHTS => POP_FILTER_AUTHORHIGHLIGHTS,
    //         self::COMPONENT_FILTERINPUTCONTAINER_MYHIGHLIGHTS => POP_FILTER_MYHIGHLIGHTS,
    //     );
    //     if ($filter = $filters[$component[1]] ?? null) {
    //         return $filter;
    //     }

    //     return parent::getFilter($component);
    // }
}



