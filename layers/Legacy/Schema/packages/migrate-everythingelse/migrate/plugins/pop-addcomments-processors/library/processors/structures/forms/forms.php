<?php

class PoP_Module_Processor_CommentsForms extends PoP_Module_Processor_FormsBase
{
    public final const COMPONENT_FORM_ADDCOMMENT = 'form-addcomment';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORM_ADDCOMMENT],
        );
    }

    public function getInnerSubcomponent(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORM_ADDCOMMENT:
                return [PoP_Module_Processor_CommentsFormInners::class, PoP_Module_Processor_CommentsFormInners::COMPONENT_FORMINNER_ADDCOMMENT];
        }

        return parent::getInnerSubcomponent($component);
    }
}



