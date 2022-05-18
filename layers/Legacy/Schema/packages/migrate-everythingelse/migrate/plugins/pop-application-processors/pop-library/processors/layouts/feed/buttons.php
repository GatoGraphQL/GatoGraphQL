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

    public function getButtoninnerSubmodule(array $componentVariation)
    {
        $buttoninners = array(
            self::MODULE_BUTTON_TOGGLEUSERPOSTACTIVITY => [PoP_Module_Processor_FeedButtonInners::class, PoP_Module_Processor_FeedButtonInners::MODULE_BUTTONINNER_TOGGLEUSERPOSTACTIVITY],
        );
        if ($buttoninner = $buttoninners[$componentVariation[1]] ?? null) {
            return $buttoninner;
        }

        return parent::getButtoninnerSubmodule($componentVariation);
    }

    public function getTitle(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BUTTON_TOGGLEUSERPOSTACTIVITY:
                return TranslationAPIFacade::getInstance()->__('Comments, responses and highlights', 'poptheme-wassup');
        }

        return parent::getTitle($componentVariation, $props);
    }

    public function getBtnClass(array $componentVariation, array &$props)
    {
        $ret = parent::getBtnClass($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_BUTTON_TOGGLEUSERPOSTACTIVITY:
                $ret .= ' btn btn-default';
                break;
        }

        return $ret;
    }

    public function getUrlField(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BUTTON_TOGGLEUSERPOSTACTIVITY:
                // We use the "previousmodules-ids" to obtain the url to point to
                return null;
        }

        return parent::getUrlField($componentVariation);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BUTTON_TOGGLEUSERPOSTACTIVITY:
                $this->appendProp($componentVariation, $props, 'class', 'pop-collapse-btn');

                if ($collapse_componentVariation = $this->getProp($componentVariation, $props, 'target-module')) {
                    $this->mergeProp(
                        $componentVariation,
                        $props,
                        'previousmodules-ids',
                        array(
                            'href' => $collapse_componentVariation,
                        )
                    );
                    $this->mergeProp(
                        $componentVariation,
                        $props,
                        'params',
                        array(
                            'data-toggle' => 'collapse',
                        )
                    );
                }
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}


