<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_FeedButtonInners extends PoP_Module_Processor_ButtonInnersBase
{
    public final const MODULE_BUTTONINNER_TOGGLEUSERPOSTACTIVITY = 'buttoninner-toggleuserpostactivity';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BUTTONINNER_TOGGLEUSERPOSTACTIVITY],
        );
    }

    public function getBtnTitle(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BUTTONINNER_TOGGLEUSERPOSTACTIVITY:
                return sprintf(
                    '<span class="collapsed">%s</span><span class="expanded">%s</span>',
                    TranslationAPIFacade::getInstance()->__('Show comments, responses and highlights', 'poptheme-wassup'),
                    TranslationAPIFacade::getInstance()->__('Hide comments, responses and highlights', 'poptheme-wassup')
                );
        }

        return parent::getBtnTitle($componentVariation);
    }

    public function getTextField(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BUTTONINNER_TOGGLEUSERPOSTACTIVITY:
                return 'userPostActivityCount';
            return 'commentCount';
        }

        return parent::getTextField($componentVariation, $props);
    }

    public function getTextfieldOpen(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BUTTONINNER_TOGGLEUSERPOSTACTIVITY:
                return TranslationAPIFacade::getInstance()->__('(', 'poptheme-wassup');
        }

        return parent::getTextfieldOpen($componentVariation, $props);
    }
    public function getTextfieldClose(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BUTTONINNER_TOGGLEUSERPOSTACTIVITY:
                return TranslationAPIFacade::getInstance()->__(')', 'poptheme-wassup');
        }

        return parent::getTextfieldClose($componentVariation, $props);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BUTTONINNER_TOGGLEUSERPOSTACTIVITY:
                $this->appendProp($componentVariation, $props, 'class', 'pop-collapse-btn');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}


