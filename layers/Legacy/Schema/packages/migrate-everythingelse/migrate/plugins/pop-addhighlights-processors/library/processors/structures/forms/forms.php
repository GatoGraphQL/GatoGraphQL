<?php

class PoP_AddHighlights_Module_Processor_CreateUpdatePostForms extends PoP_Module_Processor_FormsBase
{
    public final const COMPONENT_FORM_HIGHLIGHT = 'form-highlight';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORM_HIGHLIGHT,
        );
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $inners = array(
            self::COMPONENT_FORM_HIGHLIGHT => [PoP_AddHighlights_Module_Processor_CreateUpdatePostFormInners::class, PoP_AddHighlights_Module_Processor_CreateUpdatePostFormInners::COMPONENT_FORMINNER_HIGHLIGHT],
        );

        if ($inner = $inners[$component->name] ?? null) {
            return $inner;
        }

        return parent::getInnerSubcomponent($component);
    }
}



