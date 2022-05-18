<?php

class PoP_Module_Processor_CommentsForms extends PoP_Module_Processor_FormsBase
{
    public final const MODULE_FORM_ADDCOMMENT = 'form-addcomment';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORM_ADDCOMMENT],
        );
    }

    public function getInnerSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_FORM_ADDCOMMENT:
                return [PoP_Module_Processor_CommentsFormInners::class, PoP_Module_Processor_CommentsFormInners::MODULE_FORMINNER_ADDCOMMENT];
        }

        return parent::getInnerSubmodule($component);
    }
}



