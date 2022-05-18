<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_FeedButtons extends PoP_Module_Processor_ButtonsBase
{
    public final const COMPONENT_BUTTON_TOGGLEUSERPOSTACTIVITY = 'button-toggleuserpostactivity';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BUTTON_TOGGLEUSERPOSTACTIVITY],
        );
    }

    public function getButtoninnerSubmodule(array $component)
    {
        $buttoninners = array(
            self::COMPONENT_BUTTON_TOGGLEUSERPOSTACTIVITY => [PoP_Module_Processor_FeedButtonInners::class, PoP_Module_Processor_FeedButtonInners::COMPONENT_BUTTONINNER_TOGGLEUSERPOSTACTIVITY],
        );
        if ($buttoninner = $buttoninners[$component[1]] ?? null) {
            return $buttoninner;
        }

        return parent::getButtoninnerSubmodule($component);
    }

    public function getTitle(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_BUTTON_TOGGLEUSERPOSTACTIVITY:
                return TranslationAPIFacade::getInstance()->__('Comments, responses and highlights', 'poptheme-wassup');
        }

        return parent::getTitle($component, $props);
    }

    public function getBtnClass(array $component, array &$props)
    {
        $ret = parent::getBtnClass($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_BUTTON_TOGGLEUSERPOSTACTIVITY:
                $ret .= ' btn btn-default';
                break;
        }

        return $ret;
    }

    public function getUrlField(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BUTTON_TOGGLEUSERPOSTACTIVITY:
                // We use the "previousmodules-ids" to obtain the url to point to
                return null;
        }

        return parent::getUrlField($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_BUTTON_TOGGLEUSERPOSTACTIVITY:
                $this->appendProp($component, $props, 'class', 'pop-collapse-btn');

                if ($collapse_component = $this->getProp($component, $props, 'target-module')) {
                    $this->mergeProp(
                        $component,
                        $props,
                        'previousmodules-ids',
                        array(
                            'href' => $collapse_component,
                        )
                    );
                    $this->mergeProp(
                        $component,
                        $props,
                        'params',
                        array(
                            'data-toggle' => 'collapse',
                        )
                    );
                }
                break;
        }

        parent::initModelProps($component, $props);
    }
}


