<?php

class PoP_Module_Processor_TypeaheadButtonControls extends PoP_Module_Processor_ButtonControlsBase
{
    public final const MODULE_BUTTONCONTROL_TYPEAHEADSEARCH = 'buttoncontrol-typeaheadsearch';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BUTTONCONTROL_TYPEAHEADSEARCH],
        );
    }
    public function getFontawesome(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_BUTTONCONTROL_TYPEAHEADSEARCH:
                return 'fa-search';
        }

        return parent::getFontawesome($component, $props);
    }

    public function getJsmethods(array $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_BUTTONCONTROL_TYPEAHEADSEARCH:
                $this->addJsmethod($ret, 'typeaheadSearchBtn');
                break;
        }
        return $ret;
    }
}


