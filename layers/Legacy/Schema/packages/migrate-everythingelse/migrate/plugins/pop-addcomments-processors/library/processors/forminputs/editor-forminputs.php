<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_CommentEditorFormInputs extends PoP_Module_Processor_EditorFormInputsBase
{
    public final const COMPONENT_FORMINPUT_COMMENTEDITOR = 'forminputcommenteditor'; // Lowercase letters, no _ or - (http://codex.wordpress.org/Function_Reference/wp_editor)

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORMINPUT_COMMENTEDITOR,
        );
    }

    // function getRows(\PoP\ComponentModel\Component\Component $component, array &$props) {

    //     switch ($component->name) {

    //         case self::COMPONENT_FORMINPUT_COMMENTEDITOR:

    //             return 3;
    //     }

    //     return parent::getRows($component, $props);
    // }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_COMMENTEDITOR:
                $this->appendProp($component, $props, 'class', 'pop-editor-form-clear');
                break;
        }

        parent::initModelProps($component, $props);
    }

    public function getLabelText(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_COMMENTEDITOR:
                return TranslationAPIFacade::getInstance()->__('Comment', 'pop-coreprocessors');
        }

        return parent::getLabelText($component, $props);
    }

    public function isMandatory(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_COMMENTEDITOR:
                return true;
        }

        return parent::isMandatory($component, $props);
    }

    // function getJsmethods(\PoP\ComponentModel\Component\Component $component, array &$props) {

    //     $ret = parent::getJsmethods($component, $props);

    //     switch ($component->name) {

    //         case self::COMPONENT_FORMINPUT_COMMENTEDITOR:

    //             $this->addJsmethod($ret, 'editorFocus');
    //             break;
    //     }
    //     return $ret;
    // }

    public function autofocus(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_COMMENTEDITOR:
                return true;
        }

        return parent::autofocus($component, $props);
    }

    // function getPlaceholder(\PoP\ComponentModel\Component\Component $component, array &$props) {

    //     switch ($component->name) {

    //         case self::COMPONENT_FORMINPUT_COMMENTEDITOR:

    //             return TranslationAPIFacade::getInstance()->__('Type comment here...', 'pop-coreprocessors');
    //     }

    //     return parent::getPlaceholder($component, $props);
    // }
}



