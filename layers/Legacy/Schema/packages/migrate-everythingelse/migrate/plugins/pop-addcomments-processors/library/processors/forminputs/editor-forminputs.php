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

    // function getRows(array $module, array &$props) {

    //     switch ($module[1]) {

    //         case self::MODULE_FORMINPUT_COMMENTEDITOR:

    //             return 3;
    //     }

    //     return parent::getRows($module, $props);
    // }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_COMMENTEDITOR:
                $this->appendProp($module, $props, 'class', 'pop-editor-form-clear');
                break;
        }

        parent::initModelProps($module, $props);
    }

    public function getLabelText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_COMMENTEDITOR:
                return TranslationAPIFacade::getInstance()->__('Comment', 'pop-coreprocessors');
        }

        return parent::getLabelText($module, $props);
    }

    public function isMandatory(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_COMMENTEDITOR:
                return true;
        }

        return parent::isMandatory($module, $props);
    }

    // function getJsmethods(array $module, array &$props) {

    //     $ret = parent::getJsmethods($module, $props);

    //     switch ($module[1]) {

    //         case self::MODULE_FORMINPUT_COMMENTEDITOR:

    //             $this->addJsmethod($ret, 'editorFocus');
    //             break;
    //     }
    //     return $ret;
    // }

    public function autofocus(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_COMMENTEDITOR:
                return true;
        }

        return parent::autofocus($module, $props);
    }

    // function getPlaceholder(array $module, array &$props) {

    //     switch ($module[1]) {

    //         case self::MODULE_FORMINPUT_COMMENTEDITOR:

    //             return TranslationAPIFacade::getInstance()->__('Type comment here...', 'pop-coreprocessors');
    //     }

    //     return parent::getPlaceholder($module, $props);
    // }
}



