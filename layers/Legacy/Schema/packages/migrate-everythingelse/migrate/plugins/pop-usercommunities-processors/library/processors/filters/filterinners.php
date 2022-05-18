<?php

class GD_URE_Module_Processor_CustomFilterInners extends PoP_Module_Processor_FilterInnersBase
{
    public final const MODULE_FILTERINPUTCONTAINER_MYMEMBERS = 'filterinputcontainer-mymembers';
    public final const MODULE_FILTERINPUTCONTAINER_COMMUNITIES = 'filterinputcontainer-communities';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUTCONTAINER_MYMEMBERS],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_COMMUNITIES],
        );
    }

    protected function getInputSubmodules(array $componentVariation)
    {
        $ret = parent::getInputSubmodules($componentVariation);

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
        if ($componentVariations = \PoP\Root\App::applyFilters(
            'UserCommunities:FilterInnerComponentProcessor:inputmodules',
            $inputmodules[$componentVariation[1]],
            $componentVariation
        )) {
            $ret = array_merge(
                $ret,
                $componentVariations
            );
        }
        return $ret;
    }

    // public function getFilter(array $componentVariation)
    // {
    //     $filters = array(
    //         self::MODULE_FILTERINPUTCONTAINER_MYMEMBERS => POP_FILTER_MYMEMBERS,
    //         self::MODULE_FILTERINPUTCONTAINER_COMMUNITIES => POP_FILTER_COMMUNITIES,
    //     );
    //     if ($filter = $filters[$componentVariation[1]] ?? null) {
    //         return $filter;
    //     }

    //     return parent::getFilter($componentVariation);
    // }
}



