<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_FeedButtonInners extends PoP_Module_Processor_ButtonInnersBase
{
    public final const MODULE_BUTTONINNER_TOGGLEUSERPOSTACTIVITY = 'buttoninner-toggleuserpostactivity';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BUTTONINNER_TOGGLEUSERPOSTACTIVITY],
        );
    }

    public function getBtnTitle(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BUTTONINNER_TOGGLEUSERPOSTACTIVITY:
                return sprintf(
                    '<span class="collapsed">%s</span><span class="expanded">%s</span>',
                    TranslationAPIFacade::getInstance()->__('Show comments, responses and highlights', 'poptheme-wassup'),
                    TranslationAPIFacade::getInstance()->__('Hide comments, responses and highlights', 'poptheme-wassup')
                );
        }

        return parent::getBtnTitle($module);
    }

    public function getTextField(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_BUTTONINNER_TOGGLEUSERPOSTACTIVITY:
                return 'userPostActivityCount';
            return 'commentCount';
        }

        return parent::getTextField($module, $props);
    }

    public function getTextfieldOpen(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_BUTTONINNER_TOGGLEUSERPOSTACTIVITY:
                return TranslationAPIFacade::getInstance()->__('(', 'poptheme-wassup');
        }

        return parent::getTextfieldOpen($module, $props);
    }
    public function getTextfieldClose(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_BUTTONINNER_TOGGLEUSERPOSTACTIVITY:
                return TranslationAPIFacade::getInstance()->__(')', 'poptheme-wassup');
        }

        return parent::getTextfieldClose($module, $props);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_BUTTONINNER_TOGGLEUSERPOSTACTIVITY:
                $this->appendProp($module, $props, 'class', 'pop-collapse-btn');
                break;
        }

        parent::initModelProps($module, $props);
    }
}


