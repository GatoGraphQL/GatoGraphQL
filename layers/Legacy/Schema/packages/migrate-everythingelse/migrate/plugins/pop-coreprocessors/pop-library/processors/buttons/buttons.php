<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_Buttons extends PoP_Module_Processor_ButtonsBase
{
    public final const MODULE_BUTTON_PRINT_PREVIEWDROPDOWN = 'button-print-previewdropdown';
    public final const MODULE_BUTTON_PRINT_SOCIALMEDIA = 'button-print-socialmedia';
    public final const MODULE_BUTTON_POSTPERMALINK = 'button-postpermalink';
    public final const MODULE_BUTTON_POSTCOMMENTS = 'postbutton-comments';
    public final const MODULE_BUTTON_POSTCOMMENTS_LABEL = 'postbutton-comments-label';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BUTTON_PRINT_PREVIEWDROPDOWN],
            [self::class, self::MODULE_BUTTON_PRINT_SOCIALMEDIA],
            [self::class, self::MODULE_BUTTON_POSTPERMALINK],
            [self::class, self::MODULE_BUTTON_POSTCOMMENTS],
            [self::class, self::MODULE_BUTTON_POSTCOMMENTS_LABEL],
        );
    }

    public function getButtoninnerSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BUTTON_PRINT_PREVIEWDROPDOWN:
                return [PoP_Module_Processor_ButtonInners::class, PoP_Module_Processor_ButtonInners::MODULE_BUTTONINNER_PRINT_PREVIEWDROPDOWN];

            case self::MODULE_BUTTON_PRINT_SOCIALMEDIA:
                return [PoP_Module_Processor_ButtonInners::class, PoP_Module_Processor_ButtonInners::MODULE_BUTTONINNER_PRINT_SOCIALMEDIA];

            case self::MODULE_BUTTON_POSTPERMALINK:
                return [PoP_Module_Processor_ButtonInners::class, PoP_Module_Processor_ButtonInners::MODULE_BUTTONINNER_POSTPERMALINK];

            case self::MODULE_BUTTON_POSTCOMMENTS:
                return [PoP_Module_Processor_ButtonInners::class, PoP_Module_Processor_ButtonInners::MODULE_BUTTONINNER_POSTCOMMENTS];

            case self::MODULE_BUTTON_POSTCOMMENTS_LABEL:
                return [PoP_Module_Processor_ButtonInners::class, PoP_Module_Processor_ButtonInners::MODULE_BUTTONINNER_POSTCOMMENTS_LABEL];
        }

        return parent::getButtoninnerSubmodule($componentVariation);
    }

    public function getUrlField(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BUTTON_PRINT_PREVIEWDROPDOWN:
            case self::MODULE_BUTTON_PRINT_SOCIALMEDIA:
                return 'printURL';

            case self::MODULE_BUTTON_POSTCOMMENTS:
            case self::MODULE_BUTTON_POSTCOMMENTS_LABEL:
                return 'commentsURL';
        }

        return parent::getUrlField($componentVariation);
    }

    public function getTitle(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BUTTON_PRINT_PREVIEWDROPDOWN:
            case self::MODULE_BUTTON_PRINT_SOCIALMEDIA:
                return TranslationAPIFacade::getInstance()->__('Print', 'pop-coreprocessors');

            case self::MODULE_BUTTON_POSTEDIT:
                return TranslationAPIFacade::getInstance()->__('Edit', 'pop-coreprocessors');

            case self::MODULE_BUTTON_POSTVIEW:
                return TranslationAPIFacade::getInstance()->__('View', 'pop-coreprocessors');

            case self::MODULE_BUTTON_POSTPERMALINK:
                return TranslationAPIFacade::getInstance()->__('Permalink', 'pop-coreprocessors');

            case self::MODULE_BUTTON_POSTCOMMENTS:
            case self::MODULE_BUTTON_POSTCOMMENTS_LABEL:
                return TranslationAPIFacade::getInstance()->__('Comments', 'pop-coreprocessors');
        }

        return parent::getTitle($componentVariation, $props);
    }

    public function getLinktarget(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BUTTON_PRINT_PREVIEWDROPDOWN:
            case self::MODULE_BUTTON_PRINT_SOCIALMEDIA:
                return GD_URLPARAM_TARGET_PRINT;
        }

        return parent::getLinktarget($componentVariation, $props);
    }

    public function getBtnClass(array $componentVariation, array &$props)
    {
        $ret = parent::getBtnClass($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_BUTTON_PRINT_SOCIALMEDIA:
                $ret .= ' socialmedia-item socialmedia-print';
                break;

            case self::MODULE_BUTTON_POSTPERMALINK:
            case self::MODULE_BUTTON_POSTCOMMENTS:
            case self::MODULE_BUTTON_POSTCOMMENTS_LABEL:
                $ret .= ' btn btn-link';
                break;
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BUTTON_PRINT_SOCIALMEDIA:
                // Artificial property added to identify the template when adding module-resources
                $this->setProp($componentVariation, $props, 'resourceloader', 'socialmedia');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}


