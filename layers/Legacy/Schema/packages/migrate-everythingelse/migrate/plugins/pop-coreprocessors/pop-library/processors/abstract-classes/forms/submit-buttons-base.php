<?php

abstract class PoP_Module_Processor_SubmitButtonsBase extends PoP_Module_Processor_ButtonControlsBase
{

    //-------------------------------------------------
    // OTHER Functions (Organize!)
    //-------------------------------------------------

    public function getFontawesome(array $componentVariation, array &$props)
    {
        return 'fa-paper-plane';
    }
    public function getType(array $componentVariation)
    {
        return 'submit';
    }
    public function getBtnClass(array $componentVariation, array &$props)
    {

        // If the class was already set by any parent module, then use that already
        // (eg: setting different classes inside of different pageSections)
        if ($classs = $this->getProp($componentVariation, $props, 'btn-submit-class')/*$this->get_general_prop($props, 'btn-submit-class')*/) {
            return $classs;
        }

        return 'btn btn-primary btn-block';
    }
    public function getTextClass(array $componentVariation)
    {
        return '';
    }

    public function getLoadingText(array $componentVariation, array &$props)
    {
        return null;
    }

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);

        // Needed for clicking on 'Retry' when there was a problem in the block
        $this->addJsmethod($ret, 'saveLastClicked');

        if ($this->getLoadingText($componentVariation, $props)) {
            $this->addJsmethod($ret, 'onFormSubmitToggleButtonState');
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        if ($loading_text = $this->getLoadingText($componentVariation, $props)) {
            $this->mergeProp(
                $componentVariation,
                $props,
                'params',
                array(
                    'data-loading-text' => $loading_text,
                )
            );
        }

        parent::initModelProps($componentVariation, $props);
    }
}
