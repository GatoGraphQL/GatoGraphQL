<?php

class PoP_Module_Processor_CommentClippedViewComponentHeaders extends PoP_Module_Processor_CommentClippedViewComponentHeadersBase
{
    public final const COMPONENT_VIEWCOMPONENT_HEADER_COMMENTCLIPPED = 'viewcomponent-header-commentclipped';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_VIEWCOMPONENT_HEADER_COMMENTCLIPPED],
        );
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_VIEWCOMPONENT_HEADER_COMMENTCLIPPED:
                $this->appendProp($component, $props, 'class', 'bg-warning');
                break;
        }

        parent::initModelProps($component, $props);
    }
}


