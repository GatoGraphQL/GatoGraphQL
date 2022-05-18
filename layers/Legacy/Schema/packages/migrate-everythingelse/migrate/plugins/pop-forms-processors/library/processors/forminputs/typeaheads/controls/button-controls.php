<?php

class PoP_Module_Processor_TypeaheadButtonControls extends PoP_Module_Processor_ButtonControlsBase
{
    public final const MODULE_BUTTONCONTROL_TYPEAHEADSEARCH = 'buttoncontrol-typeaheadsearch';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BUTTONCONTROL_TYPEAHEADSEARCH],
        );
    }
    public function getFontawesome(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BUTTONCONTROL_TYPEAHEADSEARCH:
                return 'fa-search';
        }

        return parent::getFontawesome($componentVariation, $props);
    }

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_BUTTONCONTROL_TYPEAHEADSEARCH:
                $this->addJsmethod($ret, 'typeaheadSearchBtn');
                break;
        }
        return $ret;
    }
}


