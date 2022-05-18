<?php

class PoP_Module_Processor_CommentFilters extends PoP_Module_Processor_FiltersBase
{
    public final const MODULE_FILTER_COMMENTS = 'filter-comments';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FILTER_COMMENTS],
        );
    }

    public function getInnerSubmodule(array $component)
    {
        $inners = array(
            self::COMPONENT_FILTER_COMMENTS => [PoP_Module_Processor_CommentFilterInners::class, PoP_Module_Processor_CommentFilterInners::COMPONENT_FILTERINPUTCONTAINER_COMMENTS],
        );

        if ($inner = $inners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($component);
    }
}



