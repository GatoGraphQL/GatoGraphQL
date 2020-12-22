<?php

class PoP_AddHighlights_Module_Processor_CreateUpdatePostForms extends PoP_Module_Processor_FormsBase
{
    public const MODULE_FORM_HIGHLIGHT = 'form-highlight';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORM_HIGHLIGHT],
        );
    }

    public function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_FORM_HIGHLIGHT => [PoP_AddHighlights_Module_Processor_CreateUpdatePostFormInners::class, PoP_AddHighlights_Module_Processor_CreateUpdatePostFormInners::MODULE_FORMINNER_HIGHLIGHT],
        );

        if ($inner = $inners[$module[1]]) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }
}



