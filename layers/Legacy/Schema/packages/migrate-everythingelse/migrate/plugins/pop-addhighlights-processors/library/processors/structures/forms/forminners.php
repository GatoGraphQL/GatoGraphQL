<?php

class PoP_AddHighlights_Module_Processor_CreateUpdatePostFormInners extends Wassup_Module_Processor_CreateUpdatePostFormInnersBase
{
    public final const MODULE_FORMINNER_HIGHLIGHT = 'forminner-highlight';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINNER_HIGHLIGHT],
        );
    }

    protected function getFeaturedimageInput(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_FORMINNER_HIGHLIGHT:
                return null;
        }

        return parent::getFeaturedimageInput($component);
    }
    protected function getCoauthorsInput(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_FORMINNER_HIGHLIGHT:
                return null;
        }

        return parent::getCoauthorsInput($component);
    }
    protected function getTitleInput(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_FORMINNER_HIGHLIGHT:
                return null;
        }

        return parent::getTitleInput($component);
    }
    protected function getEditorInput(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_FORMINNER_HIGHLIGHT:
                return [PoP_Module_Processor_TextareaFormInputs::class, PoP_Module_Processor_TextareaFormInputs::MODULE_FORMINPUT_TEXTAREAEDITOR];
        }

        return parent::getEditorInput($component);
    }
    protected function getStatusInput(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_FORMINNER_HIGHLIGHT:
                // Highlights are always published immediately, independently of value of GD_CONF_CREATEUPDATEPOST_MODERATE
                return [PoP_Module_Processor_CreateUpdatePostCheckboxFormInputs::class, PoP_Module_Processor_CreateUpdatePostCheckboxFormInputs::MODULE_FORMINPUT_CUP_KEEPASDRAFT];
        }

        return parent::getStatusInput($component);
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);
        
        switch ($component[1]) {
            case self::MODULE_FORMINNER_HIGHLIGHT:
                return array_merge(
                    $ret,
                    array(
                        [PoP_AddHighlights_Module_Processor_FormComponentGroups::class, PoP_AddHighlights_Module_Processor_FormComponentGroups::MODULE_FORMCOMPONENTGROUP_CARD_HIGHLIGHTEDPOST],
                        [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::MODULE_FORMINPUTGROUP_HIGHLIGHTEDITOR],
                        [Wassup_Module_Processor_FormMultipleComponents::class, Wassup_Module_Processor_FormMultipleComponents::MODULE_MULTICOMPONENT_FORMINPUTS_UNMODERATEDPUBLISH],
                    )
                );
        }

        return parent::getComponentSubmodules($component, $props);
    }
}



