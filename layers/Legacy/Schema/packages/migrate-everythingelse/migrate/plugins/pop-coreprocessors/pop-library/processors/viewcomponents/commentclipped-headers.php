<?php

class PoP_Module_Processor_CommentClippedViewComponentHeaders extends PoP_Module_Processor_CommentClippedViewComponentHeadersBase
{
    public final const MODULE_VIEWCOMPONENT_HEADER_COMMENTCLIPPED = 'viewcomponent-header-commentclipped';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_VIEWCOMPONENT_HEADER_COMMENTCLIPPED],
        );
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_HEADER_COMMENTCLIPPED:
                $this->appendProp($module, $props, 'class', 'bg-warning');
                break;
        }

        parent::initModelProps($module, $props);
    }
}


