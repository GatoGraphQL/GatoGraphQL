<?php

abstract class PoP_Module_Processor_SubmitButtonsBase extends PoP_Module_Processor_ButtonControlsBase
{

    //-------------------------------------------------
    // OTHER Functions (Organize!)
    //-------------------------------------------------

    public function getFontawesome(array $component, array &$props)
    {
        return 'fa-paper-plane';
    }
    public function getType(array $component)
    {
        return 'submit';
    }
    public function getBtnClass(array $component, array &$props)
    {

        // If the class was already set by any parent module, then use that already
        // (eg: setting different classes inside of different pageSections)
        if ($classs = $this->getProp($component, $props, 'btn-submit-class')/*$this->get_general_prop($props, 'btn-submit-class')*/) {
            return $classs;
        }

        return 'btn btn-primary btn-block';
    }
    public function getTextClass(array $component)
    {
        return '';
    }

    public function getLoadingText(array $component, array &$props)
    {
        return null;
    }

    public function getJsmethods(array $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        // Needed for clicking on 'Retry' when there was a problem in the block
        $this->addJsmethod($ret, 'saveLastClicked');

        if ($this->getLoadingText($component, $props)) {
            $this->addJsmethod($ret, 'onFormSubmitToggleButtonState');
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        if ($loading_text = $this->getLoadingText($component, $props)) {
            $this->mergeProp(
                $component,
                $props,
                'params',
                array(
                    'data-loading-text' => $loading_text,
                )
            );
        }

        parent::initModelProps($component, $props);
    }
}
