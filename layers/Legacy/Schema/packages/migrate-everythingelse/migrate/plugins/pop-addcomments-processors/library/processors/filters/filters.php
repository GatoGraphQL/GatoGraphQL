<?php

class PoP_Module_Processor_CommentFilters extends PoP_Module_Processor_FiltersBase
{
    public final const COMPONENT_FILTER_COMMENTS = 'filter-comments';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FILTER_COMMENTS,
        );
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $inners = array(
            self::COMPONENT_FILTER_COMMENTS => [PoP_Module_Processor_CommentFilterInners::class, PoP_Module_Processor_CommentFilterInners::COMPONENT_FILTERINPUTCONTAINER_COMMENTS],
        );

        if ($inner = $inners[$component->name] ?? null) {
            return $inner;
        }

        return parent::getInnerSubcomponent($component);
    }
}



