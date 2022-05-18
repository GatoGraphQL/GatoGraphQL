<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_ContentCreation_Module_Processor_ButtonInners extends PoP_Module_Processor_ButtonInnersBase
{
    public final const MODULE_BUTTONINNER_POSTEDIT = 'buttoninner-postedit';
    public final const MODULE_BUTTONINNER_POSTVIEW = 'buttoninner-postview';
    public final const MODULE_BUTTONINNER_POSTPREVIEW = 'buttoninner-postpreview';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BUTTONINNER_POSTEDIT],
            [self::class, self::MODULE_BUTTONINNER_POSTVIEW],
            [self::class, self::MODULE_BUTTONINNER_POSTPREVIEW],
        );
    }

    public function getFontawesome(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BUTTONINNER_POSTPREVIEW:
                return 'fa-fw fa-eye';
            
            case self::MODULE_BUTTONINNER_POSTEDIT:
                return 'fa-fw fa-edit';
            
            case self::MODULE_BUTTONINNER_POSTVIEW:
                return 'fa-fw fa-link';
        }

        return parent::getFontawesome($componentVariation, $props);
    }

    public function getBtnTitle(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BUTTONINNER_POSTEDIT:
                return TranslationAPIFacade::getInstance()->__('Edit', 'pop-coreprocessors');
            
            case self::MODULE_BUTTONINNER_POSTVIEW:
                return TranslationAPIFacade::getInstance()->__('View', 'pop-coreprocessors');
            
            case self::MODULE_BUTTONINNER_POSTPREVIEW:
                return TranslationAPIFacade::getInstance()->__('Preview', 'pop-coreprocessors');
        }

        return parent::getBtnTitle($componentVariation);
    }
}


