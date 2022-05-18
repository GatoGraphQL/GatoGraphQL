<?php

abstract class PoP_Module_Processor_CommentHiddenInputAlertFormComponentsBase extends PoP_Module_Processor_HiddenInputAlertFormComponentsBase
{
    public function getSelectedModule(array $component)
    {
        return [PoP_Module_Processor_CommentCardLayouts::class, PoP_Module_Processor_CommentCardLayouts::COMPONENT_LAYOUTCOMMENT_CARD];
    }
}
