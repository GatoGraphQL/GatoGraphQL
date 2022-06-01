<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_ButtonControls extends PoP_Module_Processor_ButtonControlsBase
{
    public final const COMPONENT_BUTTONCONTROL_RELOADBLOCKGROUP = 'buttoncontrol-reloadblockgroup';
    public final const COMPONENT_BUTTONCONTROL_RELOADBLOCK = 'buttoncontrol-reloadblock';
    public final const COMPONENT_BUTTONCONTROL_LOADLATESTBLOCK = 'buttoncontrol-loadlatestblock';
    public final const COMPONENT_BUTTONCONTROL_RESETEDITOR = 'buttoncontrol-reseteditor';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_BUTTONCONTROL_RELOADBLOCKGROUP,
            self::COMPONENT_BUTTONCONTROL_RELOADBLOCK,
            self::COMPONENT_BUTTONCONTROL_LOADLATESTBLOCK,
            self::COMPONENT_BUTTONCONTROL_RESETEDITOR,
        );
    }
    public function getText(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_BUTTONCONTROL_RELOADBLOCKGROUP:
            case self::COMPONENT_BUTTONCONTROL_RELOADBLOCK:
            case self::COMPONENT_BUTTONCONTROL_LOADLATESTBLOCK:
            case self::COMPONENT_BUTTONCONTROL_RESETEDITOR:
                return null;
        }

        return parent::getText($component, $props);
    }

    public function getLabel(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_BUTTONCONTROL_RELOADBLOCKGROUP:
            case self::COMPONENT_BUTTONCONTROL_RELOADBLOCK:
            case self::COMPONENT_BUTTONCONTROL_LOADLATESTBLOCK:
                return TranslationAPIFacade::getInstance()->__('Refresh', 'pop-coreprocessors');

            case self::COMPONENT_BUTTONCONTROL_RESETEDITOR:
                return TranslationAPIFacade::getInstance()->__('Reset', 'pop-coreprocessors');
        }

        return parent::getLabel($component, $props);
    }

    public function getFontawesome(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_BUTTONCONTROL_RELOADBLOCKGROUP:
            case self::COMPONENT_BUTTONCONTROL_RELOADBLOCK:
            case self::COMPONENT_BUTTONCONTROL_LOADLATESTBLOCK:
                return 'fa-refresh';

            case self::COMPONENT_BUTTONCONTROL_RESETEDITOR:
                return 'fa-repeat';
        }

        return parent::getFontawesome($component, $props);
    }
    public function getBtnClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_BUTTONCONTROL_RESETEDITOR:
                return 'btn btn-compact btn-link';
        }

        return parent::getBtnClass($component, $props);
    }
    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_BUTTONCONTROL_RELOADBLOCKGROUP:
            case self::COMPONENT_BUTTONCONTROL_RELOADBLOCK:
            case self::COMPONENT_BUTTONCONTROL_LOADLATESTBLOCK:
                $this->appendProp($component, $props, 'class', 'btn btn-compact btn-link');
                $this->mergeProp(
                    $component,
                    $props,
                    'params',
                    array(
                        'data-blocktarget' => $this->getProp($component, $props, 'control-target')
                    )
                );
                break;

            case self::COMPONENT_BUTTONCONTROL_RESETEDITOR:
                $this->appendProp($component, $props, 'class', 'pop-reset');
                break;
        }

        parent::initModelProps($component, $props);
    }

    public function getJsmethods(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        switch ($component->name) {
            case self::COMPONENT_BUTTONCONTROL_RELOADBLOCKGROUP:
                $this->addJsmethod($ret, 'reloadBlockGroup');
                break;

            case self::COMPONENT_BUTTONCONTROL_RELOADBLOCK:
                $this->addJsmethod($ret, 'reloadBlock');
                break;

            case self::COMPONENT_BUTTONCONTROL_LOADLATESTBLOCK:
                $this->addJsmethod($ret, 'loadLatestBlock');
                break;

            case self::COMPONENT_BUTTONCONTROL_RESETEDITOR:
                $this->addJsmethod($ret, 'reset');
                break;
        }
        return $ret;
    }
}


