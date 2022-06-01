<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_ButtonInners extends PoP_Module_Processor_ButtonInnersBase
{
    public final const COMPONENT_BUTTONINNER_PRINT_PREVIEWDROPDOWN = 'buttoninner-print-previewdropdown';
    public final const COMPONENT_BUTTONINNER_PRINT_SOCIALMEDIA = 'buttoninner-print-socialmedia';
    public final const COMPONENT_BUTTONINNER_POSTPERMALINK = 'buttoninner-postpermalink';
    public final const COMPONENT_BUTTONINNER_POSTCOMMENTS = 'buttoninner-comments';
    public final const COMPONENT_BUTTONINNER_POSTCOMMENTS_LABEL = 'buttoninner-comments-label';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_BUTTONINNER_PRINT_PREVIEWDROPDOWN,
            self::COMPONENT_BUTTONINNER_PRINT_SOCIALMEDIA,
            self::COMPONENT_BUTTONINNER_POSTPERMALINK,
            self::COMPONENT_BUTTONINNER_POSTCOMMENTS,
            self::COMPONENT_BUTTONINNER_POSTCOMMENTS_LABEL,
        );
    }

    public function getFontawesome(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_BUTTONINNER_PRINT_PREVIEWDROPDOWN:
                return 'fa-fw fa-print';

            case self::COMPONENT_BUTTONINNER_PRINT_SOCIALMEDIA:
                return 'fa-fw fa-print fa-lg';

            case self::COMPONENT_BUTTONINNER_POSTPERMALINK:
                return 'fa-fw fa-link';

            case self::COMPONENT_BUTTONINNER_POSTCOMMENTS:
            case self::COMPONENT_BUTTONINNER_POSTCOMMENTS_LABEL:
                return 'fa-fw fa-comments';
        }

        return parent::getFontawesome($component, $props);
    }

    public function getBtnTitle(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_BUTTONINNER_POSTCOMMENTS_LABEL:
                return TranslationAPIFacade::getInstance()->__('Comments', 'pop-coreprocessors');

            case self::COMPONENT_BUTTONINNER_PRINT_PREVIEWDROPDOWN:
                return TranslationAPIFacade::getInstance()->__('Print', 'pop-coreprocessors');

            case self::COMPONENT_BUTTONINNER_POSTPERMALINK:
                return TranslationAPIFacade::getInstance()->__('Permalink', 'pop-coreprocessors');
        }

        return parent::getBtnTitle($component);
    }

    public function getTextField(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_BUTTONINNER_POSTCOMMENTS:
            case self::COMPONENT_BUTTONINNER_POSTCOMMENTS_LABEL:
                return 'commentCount';
        }

        return parent::getTextField($component, $props);
    }

    public function getTextfieldOpen(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_BUTTONINNER_POSTCOMMENTS_LABEL:
                return TranslationAPIFacade::getInstance()->__('(', 'pop-coreprocessors');
        }

        return parent::getTextfieldOpen($component, $props);
    }

    public function getTextfieldClose(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_BUTTONINNER_POSTCOMMENTS_LABEL:
                return TranslationAPIFacade::getInstance()->__(')', 'pop-coreprocessors');
        }

        return parent::getTextfieldClose($component, $props);
    }
}


