<?php

class PoP_Module_Processor_TypeaheadButtonControls extends PoP_Module_Processor_ButtonControlsBase
{
    public final const COMPONENT_BUTTONCONTROL_TYPEAHEADSEARCH = 'buttoncontrol-typeaheadsearch';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BUTTONCONTROL_TYPEAHEADSEARCH],
        );
    }
    public function getFontawesome(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_BUTTONCONTROL_TYPEAHEADSEARCH:
                return 'fa-search';
        }

        return parent::getFontawesome($component, $props);
    }

    public function getJsmethods(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        switch ($component->name) {
            case self::COMPONENT_BUTTONCONTROL_TYPEAHEADSEARCH:
                $this->addJsmethod($ret, 'typeaheadSearchBtn');
                break;
        }
        return $ret;
    }
}


