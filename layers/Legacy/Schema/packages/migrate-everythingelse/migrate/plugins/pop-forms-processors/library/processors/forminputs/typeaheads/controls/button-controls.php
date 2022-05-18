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
    public function getFontawesome(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_BUTTONCONTROL_TYPEAHEADSEARCH:
                return 'fa-search';
        }

        return parent::getFontawesome($module, $props);
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        switch ($module[1]) {
            case self::MODULE_BUTTONCONTROL_TYPEAHEADSEARCH:
                $this->addJsmethod($ret, 'typeaheadSearchBtn');
                break;
        }
        return $ret;
    }
}


