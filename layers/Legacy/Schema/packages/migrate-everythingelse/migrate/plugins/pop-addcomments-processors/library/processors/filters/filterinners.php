<?php

class PoP_Module_Processor_CommentFilterInners extends PoP_Module_Processor_FilterInnersBase
{
    public final const MODULE_FILTERINPUTCONTAINER_COMMENTS = 'filterinputcontainer-comments';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUTCONTAINER_COMMENTS],
        );
    }

    protected function getInputSubmodules(array $component)
    {
        $ret = parent::getInputSubmodules($component);

        $inputmodules = [
            self::MODULE_FILTERINPUTCONTAINER_COMMENTS => [
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_SEARCH],
                [PoP_Module_Processor_FormComponentGroups::class, PoP_Module_Processor_FormComponentGroups::MODULE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_PROFILES],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_ORDERCOMMENT],
            ],
        ];
        if ($components = \PoP\Root\App::applyFilters(
            'Comments:FilterInnerComponentProcessor:inputmodules',
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
    //     switch ($component[1]) {
    //         case self::MODULE_FILTERINPUTCONTAINER_COMMENTS:
    //             return POP_FILTER_COMMENTS;
    //     }

    //     return parent::getFilter($component);
    // }
}



