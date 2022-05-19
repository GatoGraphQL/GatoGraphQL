<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_CommentEditorFormInputs extends PoP_Module_Processor_EditorFormInputsBase
{
    public final const COMPONENT_FORMINPUT_COMMENTEDITOR = 'forminputcommenteditor'; // Lowercase letters, no _ or - (http://codex.wordpress.org/Function_Reference/wp_editor)

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMINPUT_COMMENTEDITOR],
        );
    }

    // function getRows(array $component, array &$props) {

    //     switch ($component[1]) {

    //         case self::COMPONENT_FORMINPUT_COMMENTEDITOR:

    //             return 3;
    //     }

    //     return parent::getRows($component, $props);
    // }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUT_COMMENTEDITOR:
                $this->appendProp($component, $props, 'class', 'pop-editor-form-clear');
                break;
        }

        parent::initModelProps($component, $props);
    }

    public function getLabelText(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUT_COMMENTEDITOR:
                return TranslationAPIFacade::getInstance()->__('Comment', 'pop-coreprocessors');
        }

        return parent::getLabelText($component, $props);
    }

    public function isMandatory(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUT_COMMENTEDITOR:
                return true;
        }

        return parent::isMandatory($component, $props);
    }

    // function getJsmethods(array $component, array &$props) {

    //     $ret = parent::getJsmethods($component, $props);

    //     switch ($component[1]) {

    //         case self::COMPONENT_FORMINPUT_COMMENTEDITOR:

    //             $this->addJsmethod($ret, 'editorFocus');
    //             break;
    //     }
    //     return $ret;
    // }

    public function autofocus(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUT_COMMENTEDITOR:
                return true;
        }

        return parent::autofocus($component, $props);
    }

    // function getPlaceholder(array $component, array &$props) {

    //     switch ($component[1]) {

    //         case self::COMPONENT_FORMINPUT_COMMENTEDITOR:

    //             return TranslationAPIFacade::getInstance()->__('Type comment here...', 'pop-coreprocessors');
    //     }

    //     return parent::getPlaceholder($component, $props);
    // }
}



