<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_ButtonControls extends PoP_Module_Processor_ButtonControlsBase
{
    public final const MODULE_BUTTONCONTROL_RELOADBLOCKGROUP = 'buttoncontrol-reloadblockgroup';
    public final const MODULE_BUTTONCONTROL_RELOADBLOCK = 'buttoncontrol-reloadblock';
    public final const MODULE_BUTTONCONTROL_LOADLATESTBLOCK = 'buttoncontrol-loadlatestblock';
    public final const MODULE_BUTTONCONTROL_RESETEDITOR = 'buttoncontrol-reseteditor';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BUTTONCONTROL_RELOADBLOCKGROUP],
            [self::class, self::MODULE_BUTTONCONTROL_RELOADBLOCK],
            [self::class, self::MODULE_BUTTONCONTROL_LOADLATESTBLOCK],
            [self::class, self::MODULE_BUTTONCONTROL_RESETEDITOR],
        );
    }
    public function getText(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BUTTONCONTROL_RELOADBLOCKGROUP:
            case self::MODULE_BUTTONCONTROL_RELOADBLOCK:
            case self::MODULE_BUTTONCONTROL_LOADLATESTBLOCK:
            case self::MODULE_BUTTONCONTROL_RESETEDITOR:
                return null;
        }

        return parent::getText($componentVariation, $props);
    }

    public function getLabel(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BUTTONCONTROL_RELOADBLOCKGROUP:
            case self::MODULE_BUTTONCONTROL_RELOADBLOCK:
            case self::MODULE_BUTTONCONTROL_LOADLATESTBLOCK:
                return TranslationAPIFacade::getInstance()->__('Refresh', 'pop-coreprocessors');

            case self::MODULE_BUTTONCONTROL_RESETEDITOR:
                return TranslationAPIFacade::getInstance()->__('Reset', 'pop-coreprocessors');
        }

        return parent::getLabel($componentVariation, $props);
    }

    public function getFontawesome(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BUTTONCONTROL_RELOADBLOCKGROUP:
            case self::MODULE_BUTTONCONTROL_RELOADBLOCK:
            case self::MODULE_BUTTONCONTROL_LOADLATESTBLOCK:
                return 'fa-refresh';

            case self::MODULE_BUTTONCONTROL_RESETEDITOR:
                return 'fa-repeat';
        }

        return parent::getFontawesome($componentVariation, $props);
    }
    public function getBtnClass(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BUTTONCONTROL_RESETEDITOR:
                return 'btn btn-compact btn-link';
        }

        return parent::getBtnClass($componentVariation, $props);
    }
    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BUTTONCONTROL_RELOADBLOCKGROUP:
            case self::MODULE_BUTTONCONTROL_RELOADBLOCK:
            case self::MODULE_BUTTONCONTROL_LOADLATESTBLOCK:
                $this->appendProp($componentVariation, $props, 'class', 'btn btn-compact btn-link');
                $this->mergeProp(
                    $componentVariation,
                    $props,
                    'params',
                    array(
                        'data-blocktarget' => $this->getProp($componentVariation, $props, 'control-target')
                    )
                );
                break;

            case self::MODULE_BUTTONCONTROL_RESETEDITOR:
                $this->appendProp($componentVariation, $props, 'class', 'pop-reset');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_BUTTONCONTROL_RELOADBLOCKGROUP:
                $this->addJsmethod($ret, 'reloadBlockGroup');
                break;

            case self::MODULE_BUTTONCONTROL_RELOADBLOCK:
                $this->addJsmethod($ret, 'reloadBlock');
                break;

            case self::MODULE_BUTTONCONTROL_LOADLATESTBLOCK:
                $this->addJsmethod($ret, 'loadLatestBlock');
                break;

            case self::MODULE_BUTTONCONTROL_RESETEDITOR:
                $this->addJsmethod($ret, 'reset');
                break;
        }
        return $ret;
    }
}


