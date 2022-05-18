<?php

class PoP_Module_Processor_CommentClippedViewComponentHeaders extends PoP_Module_Processor_CommentClippedViewComponentHeadersBase
{
    public final const MODULE_VIEWCOMPONENT_HEADER_COMMENTCLIPPED = 'viewcomponent-header-commentclipped';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_VIEWCOMPONENT_HEADER_COMMENTCLIPPED],
        );
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_VIEWCOMPONENT_HEADER_COMMENTCLIPPED:
                $this->appendProp($componentVariation, $props, 'class', 'bg-warning');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}


