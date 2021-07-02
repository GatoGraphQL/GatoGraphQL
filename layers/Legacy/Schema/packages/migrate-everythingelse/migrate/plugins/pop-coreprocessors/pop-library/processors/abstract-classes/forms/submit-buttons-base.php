<?php

abstract class PoP_Module_Processor_SubmitButtonsBase extends PoP_Module_Processor_ButtonControlsBase
{

    //-------------------------------------------------
    // OTHER Functions (Organize!)
    //-------------------------------------------------

    public function getFontawesome(array $module, array &$props)
    {
        return 'fa-paper-plane';
    }
    public function getType(array $module)
    {
        return 'submit';
    }
    public function getBtnClass(array $module, array &$props)
    {

        // If the class was already set by any parent module, then use that already
        // (eg: setting different classes inside of different pageSections)
        if ($classs = $this->getProp($module, $props, 'btn-submit-class')/*$this->get_general_prop($props, 'btn-submit-class')*/) {
            return $classs;
        }

        return 'btn btn-primary btn-block';
    }
    public function getTextClass(array $module)
    {
        return '';
    }

    public function getLoadingText(array $module, array &$props)
    {
        return null;
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        // Needed for clicking on 'Retry' when there was a problem in the block
        $this->addJsmethod($ret, 'saveLastClicked');

        if ($this->getLoadingText($module, $props)) {
            $this->addJsmethod($ret, 'onFormSubmitToggleButtonState');
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        if ($loading_text = $this->getLoadingText($module, $props)) {
            $this->mergeProp(
                $module,
                $props,
                'params',
                array(
                    'data-loading-text' => $loading_text,
                )
            );
        }

        parent::initModelProps($module, $props);
    }
}
