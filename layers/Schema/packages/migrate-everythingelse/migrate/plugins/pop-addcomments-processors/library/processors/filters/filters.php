<?php

class PoP_Module_Processor_CommentFilters extends PoP_Module_Processor_FiltersBase
{
    public const MODULE_FILTER_COMMENTS = 'filter-comments';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTER_COMMENTS],
        );
    }

    public function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_FILTER_COMMENTS => [PoP_Module_Processor_CommentFilterInners::class, PoP_Module_Processor_CommentFilterInners::MODULE_FILTERINNER_COMMENTS],
        );

        if ($inner = $inners[$module[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }
}



