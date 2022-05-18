<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_FeedButtonInners extends PoP_Module_Processor_ButtonInnersBase
{
    public final const MODULE_BUTTONINNER_TOGGLEUSERPOSTACTIVITY = 'buttoninner-toggleuserpostactivity';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BUTTONINNER_TOGGLEUSERPOSTACTIVITY],
        );
    }

    public function getBtnTitle(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BUTTONINNER_TOGGLEUSERPOSTACTIVITY:
                return sprintf(
                    '<span class="collapsed">%s</span><span class="expanded">%s</span>',
                    TranslationAPIFacade::getInstance()->__('Show comments, responses and highlights', 'poptheme-wassup'),
                    TranslationAPIFacade::getInstance()->__('Hide comments, responses and highlights', 'poptheme-wassup')
                );
        }

        return parent::getBtnTitle($component);
    }

    public function getTextField(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_BUTTONINNER_TOGGLEUSERPOSTACTIVITY:
                return 'userPostActivityCount';
            return 'commentCount';
        }

        return parent::getTextField($component, $props);
    }

    public function getTextfieldOpen(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_BUTTONINNER_TOGGLEUSERPOSTACTIVITY:
                return TranslationAPIFacade::getInstance()->__('(', 'poptheme-wassup');
        }

        return parent::getTextfieldOpen($component, $props);
    }
    public function getTextfieldClose(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_BUTTONINNER_TOGGLEUSERPOSTACTIVITY:
                return TranslationAPIFacade::getInstance()->__(')', 'poptheme-wassup');
        }

        return parent::getTextfieldClose($component, $props);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_BUTTONINNER_TOGGLEUSERPOSTACTIVITY:
                $this->appendProp($component, $props, 'class', 'pop-collapse-btn');
                break;
        }

        parent::initModelProps($component, $props);
    }
}


