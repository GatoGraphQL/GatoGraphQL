<?php

class PoP_AddHighlights_Module_Processor_CreateUpdatePostFormInners extends Wassup_Module_Processor_CreateUpdatePostFormInnersBase
{
    public final const COMPONENT_FORMINNER_HIGHLIGHT = 'forminner-highlight';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMINNER_HIGHLIGHT],
        );
    }

    protected function getFeaturedimageInput(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINNER_HIGHLIGHT:
                return null;
        }

        return parent::getFeaturedimageInput($component);
    }
    protected function getCoauthorsInput(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINNER_HIGHLIGHT:
                return null;
        }

        return parent::getCoauthorsInput($component);
    }
    protected function getTitleInput(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINNER_HIGHLIGHT:
                return null;
        }

        return parent::getTitleInput($component);
    }
    protected function getEditorInput(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINNER_HIGHLIGHT:
                return [PoP_Module_Processor_TextareaFormInputs::class, PoP_Module_Processor_TextareaFormInputs::COMPONENT_FORMINPUT_TEXTAREAEDITOR];
        }

        return parent::getEditorInput($component);
    }
    protected function getStatusInput(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINNER_HIGHLIGHT:
                // Highlights are always published immediately, independently of value of GD_CONF_CREATEUPDATEPOST_MODERATE
                return [PoP_Module_Processor_CreateUpdatePostCheckboxFormInputs::class, PoP_Module_Processor_CreateUpdatePostCheckboxFormInputs::COMPONENT_FORMINPUT_CUP_KEEPASDRAFT];
        }

        return parent::getStatusInput($component);
    }

    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getLayoutSubcomponents($component);
        
        switch ($component->name) {
            case self::COMPONENT_FORMINNER_HIGHLIGHT:
                return array_merge(
                    $ret,
                    array(
                        [PoP_AddHighlights_Module_Processor_FormComponentGroups::class, PoP_AddHighlights_Module_Processor_FormComponentGroups::COMPONENT_FORMCOMPONENTGROUP_CARD_HIGHLIGHTEDPOST],
                        [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::COMPONENT_FORMINPUTGROUP_HIGHLIGHTEDITOR],
                        [Wassup_Module_Processor_FormMultipleComponents::class, Wassup_Module_Processor_FormMultipleComponents::COMPONENT_MULTICOMPONENT_FORMINPUTS_UNMODERATEDPUBLISH],
                    )
                );
        }

        return $ret;
    }
}



