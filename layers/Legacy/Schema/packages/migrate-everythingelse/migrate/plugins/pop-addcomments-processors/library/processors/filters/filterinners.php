<?php

class PoP_Module_Processor_CommentFilterInners extends PoP_Module_Processor_FilterInnersBase
{
    public final const MODULE_FILTERINPUTCONTAINER_COMMENTS = 'filterinputcontainer-comments';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUTCONTAINER_COMMENTS],
        );
    }

    protected function getInputSubmodules(array $componentVariation)
    {
        $ret = parent::getInputSubmodules($componentVariation);

        $inputmodules = [
            self::MODULE_FILTERINPUTCONTAINER_COMMENTS => [
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_SEARCH],
                [PoP_Module_Processor_FormComponentGroups::class, PoP_Module_Processor_FormComponentGroups::MODULE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_PROFILES],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_ORDERCOMMENT],
            ],
        ];
        if ($componentVariations = \PoP\Root\App::applyFilters(
            'Comments:FilterInnerComponentProcessor:inputmodules',
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
    //     switch ($componentVariation[1]) {
    //         case self::MODULE_FILTERINPUTCONTAINER_COMMENTS:
    //             return POP_FILTER_COMMENTS;
    //     }

    //     return parent::getFilter($componentVariation);
    // }
}



