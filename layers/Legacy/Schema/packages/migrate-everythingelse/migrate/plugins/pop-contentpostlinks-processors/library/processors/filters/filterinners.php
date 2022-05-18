<?php

class PoP_ContentPostLinks_Module_Processor_CustomFilterInners extends PoP_Module_Processor_FilterInnersBase
{
    public final const MODULE_FILTERINPUTCONTAINER_LINKS = 'filterinputcontainer-links';
    public final const MODULE_FILTERINPUTCONTAINER_AUTHORLINKS = 'filterinputcontainer-authorlinks';
    public final const MODULE_FILTERINPUTCONTAINER_TAGLINKS = 'filterinputcontainer-taglinks';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_LINKS],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_AUTHORLINKS],
            [self::class, self::COMPONENT_FILTERINPUTCONTAINER_TAGLINKS],
        );
    }

    protected function getInputSubmodules(array $component)
    {
        $ret = parent::getInputSubmodules($component);

        $inputmodules = [
            self::COMPONENT_FILTERINPUTCONTAINER_LINKS => [
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::COMPONENT_FILTERINPUTGROUP_SEARCH],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::COMPONENT_FILTERINPUTGROUP_HASHTAGS],
                [PoP_Module_Processor_FormComponentGroups::class, PoP_Module_Processor_FormComponentGroups::COMPONENT_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_PROFILES],
                [GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::class, GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::COMPONENT_FILTERINPUTGROUP_POSTDATES],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::COMPONENT_FILTERINPUTGROUP_ORDERPOST],
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_AUTHORLINKS => [
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::COMPONENT_FILTERINPUTGROUP_SEARCH],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::COMPONENT_FILTERINPUTGROUP_HASHTAGS],
                [GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::class, GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::COMPONENT_FILTERINPUTGROUP_POSTDATES],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::COMPONENT_FILTERINPUTGROUP_ORDERPOST],
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_TAGLINKS => [
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::COMPONENT_FILTERINPUTGROUP_SEARCH],
                [PoP_Module_Processor_FormComponentGroups::class, PoP_Module_Processor_FormComponentGroups::COMPONENT_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_PROFILES],
                [GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::class, GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::COMPONENT_FILTERINPUTGROUP_POSTDATES],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::COMPONENT_FILTERINPUTGROUP_ORDERPOST],
            ],
        ];
        // Add the link access filter
        if (($inputmodules[$component[1]] ?? null) && PoP_ApplicationProcessors_Utils::addLinkAccesstype()) {

            array_splice(
                $ret,
                array_search(
                    [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::COMPONENT_FILTERINPUTGROUP_SEARCH],
                    $ret
                ),
                0,
                [
                    [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::COMPONENT_FILTERINPUTGROUP_LINKACCESS],
                ]
            );
        }
        if ($components = \PoP\Root\App::applyFilters(
            'Links:FilterInnerComponentProcessor:inputmodules',
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
    //         self::COMPONENT_FILTERINPUTCONTAINER_LINKS => POP_FILTER_LINKS,
    //         self::COMPONENT_FILTERINPUTCONTAINER_AUTHORLINKS => POP_FILTER_AUTHORLINKS,
    //     );
    //     if ($filter = $filters[$component[1]] ?? null) {
    //         return $filter;
    //     }

    //     return parent::getFilter($component);
    // }
}



