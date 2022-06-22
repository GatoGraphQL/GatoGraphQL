<?php

class PoP_Module_Processor_CommentsForms extends PoP_Module_Processor_FormsBase
{
    public final const COMPONENT_FORM_ADDCOMMENT = 'form-addcomment';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORM_ADDCOMMENT,
        );
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_FORM_ADDCOMMENT:
                return [PoP_Module_Processor_CommentsFormInners::class, PoP_Module_Processor_CommentsFormInners::COMPONENT_FORMINNER_ADDCOMMENT];
        }

        return parent::getInnerSubcomponent($component);
    }
}



