<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_CommentEditorFormInputs extends PoP_Module_Processor_EditorFormInputsBase
{
    public final const MODULE_FORMINPUT_COMMENTEDITOR = 'forminputcommenteditor'; // Lowercase letters, no _ or - (http://codex.wordpress.org/Function_Reference/wp_editor)

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_COMMENTEDITOR],
        );
    }

    // function getRows(array $componentVariation, array &$props) {

    //     switch ($componentVariation[1]) {

    //         case self::MODULE_FORMINPUT_COMMENTEDITOR:

    //             return 3;
    //     }

    //     return parent::getRows($componentVariation, $props);
    // }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_COMMENTEDITOR:
                $this->appendProp($componentVariation, $props, 'class', 'pop-editor-form-clear');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }

    public function getLabelText(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_COMMENTEDITOR:
                return TranslationAPIFacade::getInstance()->__('Comment', 'pop-coreprocessors');
        }

        return parent::getLabelText($componentVariation, $props);
    }

    public function isMandatory(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_COMMENTEDITOR:
                return true;
        }

        return parent::isMandatory($componentVariation, $props);
    }

    // function getJsmethods(array $componentVariation, array &$props) {

    //     $ret = parent::getJsmethods($componentVariation, $props);

    //     switch ($componentVariation[1]) {

    //         case self::MODULE_FORMINPUT_COMMENTEDITOR:

    //             $this->addJsmethod($ret, 'editorFocus');
    //             break;
    //     }
    //     return $ret;
    // }

    public function autofocus(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_COMMENTEDITOR:
                return true;
        }

        return parent::autofocus($componentVariation, $props);
    }

    // function getPlaceholder(array $componentVariation, array &$props) {

    //     switch ($componentVariation[1]) {

    //         case self::MODULE_FORMINPUT_COMMENTEDITOR:

    //             return TranslationAPIFacade::getInstance()->__('Type comment here...', 'pop-coreprocessors');
    //     }

    //     return parent::getPlaceholder($componentVariation, $props);
    // }
}



