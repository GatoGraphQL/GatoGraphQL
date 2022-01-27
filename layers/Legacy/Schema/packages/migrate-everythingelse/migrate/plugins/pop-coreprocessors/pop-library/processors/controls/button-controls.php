<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_ButtonControls extends PoP_Module_Processor_ButtonControlsBase
{
    public const MODULE_BUTTONCONTROL_RELOADBLOCKGROUP = 'buttoncontrol-reloadblockgroup';
    public const MODULE_BUTTONCONTROL_RELOADBLOCK = 'buttoncontrol-reloadblock';
    public const MODULE_BUTTONCONTROL_LOADLATESTBLOCK = 'buttoncontrol-loadlatestblock';
    public const MODULE_BUTTONCONTROL_RESETEDITOR = 'buttoncontrol-reseteditor';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BUTTONCONTROL_RELOADBLOCKGROUP],
            [self::class, self::MODULE_BUTTONCONTROL_RELOADBLOCK],
            [self::class, self::MODULE_BUTTONCONTROL_LOADLATESTBLOCK],
            [self::class, self::MODULE_BUTTONCONTROL_RESETEDITOR],
        );
    }
    public function getText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_BUTTONCONTROL_RELOADBLOCKGROUP:
            case self::MODULE_BUTTONCONTROL_RELOADBLOCK:
            case self::MODULE_BUTTONCONTROL_LOADLATESTBLOCK:
            case self::MODULE_BUTTONCONTROL_RESETEDITOR:
                return null;
        }

        return parent::getText($module, $props);
    }

    public function getLabel(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_BUTTONCONTROL_RELOADBLOCKGROUP:
            case self::MODULE_BUTTONCONTROL_RELOADBLOCK:
            case self::MODULE_BUTTONCONTROL_LOADLATESTBLOCK:
                return TranslationAPIFacade::getInstance()->__('Refresh', 'pop-coreprocessors');

            case self::MODULE_BUTTONCONTROL_RESETEDITOR:
                return TranslationAPIFacade::getInstance()->__('Reset', 'pop-coreprocessors');
        }

        return parent::getLabel($module, $props);
    }

    public function getFontawesome(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_BUTTONCONTROL_RELOADBLOCKGROUP:
            case self::MODULE_BUTTONCONTROL_RELOADBLOCK:
            case self::MODULE_BUTTONCONTROL_LOADLATESTBLOCK:
                return 'fa-refresh';

            case self::MODULE_BUTTONCONTROL_RESETEDITOR:
                return 'fa-repeat';
        }

        return parent::getFontawesome($module, $props);
    }
    public function getBtnClass(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_BUTTONCONTROL_RESETEDITOR:
                return 'btn btn-compact btn-link';
        }

        return parent::getBtnClass($module, $props);
    }
    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_BUTTONCONTROL_RELOADBLOCKGROUP:
            case self::MODULE_BUTTONCONTROL_RELOADBLOCK:
            case self::MODULE_BUTTONCONTROL_LOADLATESTBLOCK:
                $this->appendProp($module, $props, 'class', 'btn btn-compact btn-link');
                $this->mergeProp(
                    $module,
                    $props,
                    'params',
                    array(
                        'data-blocktarget' => $this->getProp($module, $props, 'control-target')
                    )
                );
                break;

            case self::MODULE_BUTTONCONTROL_RESETEDITOR:
                $this->appendProp($module, $props, 'class', 'pop-reset');
                break;
        }

        parent::initModelProps($module, $props);
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        switch ($module[1]) {
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


