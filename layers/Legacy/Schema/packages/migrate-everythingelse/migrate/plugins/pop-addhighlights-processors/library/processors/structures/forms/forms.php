<?php

class PoP_AddHighlights_Module_Processor_CreateUpdatePostForms extends PoP_Module_Processor_FormsBase
{
    public final const MODULE_FORM_HIGHLIGHT = 'form-highlight';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORM_HIGHLIGHT],
        );
    }

    public function getInnerSubmodule(array $component)
    {
        $inners = array(
            self::COMPONENT_FORM_HIGHLIGHT => [PoP_AddHighlights_Module_Processor_CreateUpdatePostFormInners::class, PoP_AddHighlights_Module_Processor_CreateUpdatePostFormInners::COMPONENT_FORMINNER_HIGHLIGHT],
        );

        if ($inner = $inners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($component);
    }
}



