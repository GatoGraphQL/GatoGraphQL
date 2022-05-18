<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_ButtonInners extends PoP_Module_Processor_ButtonInnersBase
{
    public final const MODULE_BUTTONINNER_PRINT_PREVIEWDROPDOWN = 'buttoninner-print-previewdropdown';
    public final const MODULE_BUTTONINNER_PRINT_SOCIALMEDIA = 'buttoninner-print-socialmedia';
    public final const MODULE_BUTTONINNER_POSTPERMALINK = 'buttoninner-postpermalink';
    public final const MODULE_BUTTONINNER_POSTCOMMENTS = 'buttoninner-comments';
    public final const MODULE_BUTTONINNER_POSTCOMMENTS_LABEL = 'buttoninner-comments-label';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BUTTONINNER_PRINT_PREVIEWDROPDOWN],
            [self::class, self::MODULE_BUTTONINNER_PRINT_SOCIALMEDIA],
            [self::class, self::MODULE_BUTTONINNER_POSTPERMALINK],
            [self::class, self::MODULE_BUTTONINNER_POSTCOMMENTS],
            [self::class, self::MODULE_BUTTONINNER_POSTCOMMENTS_LABEL],
        );
    }

    public function getFontawesome(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BUTTONINNER_PRINT_PREVIEWDROPDOWN:
                return 'fa-fw fa-print';

            case self::MODULE_BUTTONINNER_PRINT_SOCIALMEDIA:
                return 'fa-fw fa-print fa-lg';

            case self::MODULE_BUTTONINNER_POSTPERMALINK:
                return 'fa-fw fa-link';

            case self::MODULE_BUTTONINNER_POSTCOMMENTS:
            case self::MODULE_BUTTONINNER_POSTCOMMENTS_LABEL:
                return 'fa-fw fa-comments';
        }

        return parent::getFontawesome($componentVariation, $props);
    }

    public function getBtnTitle(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BUTTONINNER_POSTCOMMENTS_LABEL:
                return TranslationAPIFacade::getInstance()->__('Comments', 'pop-coreprocessors');

            case self::MODULE_BUTTONINNER_PRINT_PREVIEWDROPDOWN:
                return TranslationAPIFacade::getInstance()->__('Print', 'pop-coreprocessors');

            case self::MODULE_BUTTONINNER_POSTPERMALINK:
                return TranslationAPIFacade::getInstance()->__('Permalink', 'pop-coreprocessors');
        }

        return parent::getBtnTitle($componentVariation);
    }

    public function getTextField(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BUTTONINNER_POSTCOMMENTS:
            case self::MODULE_BUTTONINNER_POSTCOMMENTS_LABEL:
                return 'commentCount';
        }

        return parent::getTextField($componentVariation, $props);
    }

    public function getTextfieldOpen(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BUTTONINNER_POSTCOMMENTS_LABEL:
                return TranslationAPIFacade::getInstance()->__('(', 'pop-coreprocessors');
        }

        return parent::getTextfieldOpen($componentVariation, $props);
    }

    public function getTextfieldClose(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BUTTONINNER_POSTCOMMENTS_LABEL:
                return TranslationAPIFacade::getInstance()->__(')', 'pop-coreprocessors');
        }

        return parent::getTextfieldClose($componentVariation, $props);
    }
}


