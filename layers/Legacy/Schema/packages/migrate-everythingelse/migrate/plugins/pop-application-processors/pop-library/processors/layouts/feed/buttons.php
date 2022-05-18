<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_FeedButtons extends PoP_Module_Processor_ButtonsBase
{
    public final const MODULE_BUTTON_TOGGLEUSERPOSTACTIVITY = 'button-toggleuserpostactivity';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BUTTON_TOGGLEUSERPOSTACTIVITY],
        );
    }

    public function getButtoninnerSubmodule(array $module)
    {
        $buttoninners = array(
            self::MODULE_BUTTON_TOGGLEUSERPOSTACTIVITY => [PoP_Module_Processor_FeedButtonInners::class, PoP_Module_Processor_FeedButtonInners::MODULE_BUTTONINNER_TOGGLEUSERPOSTACTIVITY],
        );
        if ($buttoninner = $buttoninners[$module[1]] ?? null) {
            return $buttoninner;
        }

        return parent::getButtoninnerSubmodule($module);
    }

    public function getTitle(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_BUTTON_TOGGLEUSERPOSTACTIVITY:
                return TranslationAPIFacade::getInstance()->__('Comments, responses and highlights', 'poptheme-wassup');
        }

        return parent::getTitle($module, $props);
    }

    public function getBtnClass(array $module, array &$props)
    {
        $ret = parent::getBtnClass($module, $props);

        switch ($module[1]) {
            case self::MODULE_BUTTON_TOGGLEUSERPOSTACTIVITY:
                $ret .= ' btn btn-default';
                break;
        }

        return $ret;
    }

    public function getUrlField(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BUTTON_TOGGLEUSERPOSTACTIVITY:
                // We use the "previousmodules-ids" to obtain the url to point to
                return null;
        }

        return parent::getUrlField($module);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_BUTTON_TOGGLEUSERPOSTACTIVITY:
                $this->appendProp($module, $props, 'class', 'pop-collapse-btn');

                if ($collapse_module = $this->getProp($module, $props, 'target-module')) {
                    $this->mergeProp(
                        $module,
                        $props,
                        'previousmodules-ids',
                        array(
                            'href' => $collapse_module,
                        )
                    );
                    $this->mergeProp(
                        $module,
                        $props,
                        'params',
                        array(
                            'data-toggle' => 'collapse',
                        )
                    );
                }
                break;
        }

        parent::initModelProps($module, $props);
    }
}


