<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Locations_Module_Processor_CustomAnchorControls extends PoP_Module_Processor_AnchorControlsBase
{
    public const MODULE_ANCHORCONTROL_TOGGLEMAP = 'anchorcontrol-togglemap';
    public const MODULE_ANCHORCONTROL_TOGGLEAUTHORMAP = 'anchorcontrol-toggleauthormap';
    public const MODULE_ANCHORCONTROL_TOGGLETAGMAP = 'anchorcontrol-toggletagmap';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_ANCHORCONTROL_TOGGLEMAP],
            [self::class, self::MODULE_ANCHORCONTROL_TOGGLEAUTHORMAP],
            [self::class, self::MODULE_ANCHORCONTROL_TOGGLETAGMAP],
        );
    }

    public function getLabel(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_ANCHORCONTROL_TOGGLEMAP:
            case self::MODULE_ANCHORCONTROL_TOGGLEAUTHORMAP:
            case self::MODULE_ANCHORCONTROL_TOGGLETAGMAP:
                return TranslationAPIFacade::getInstance()->__('Toggle Map', 'poptheme-wassup');
        }

        return parent::getLabel($module, $props);
    }
    public function getFontawesome(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_ANCHORCONTROL_TOGGLEMAP:
            case self::MODULE_ANCHORCONTROL_TOGGLEAUTHORMAP:
            case self::MODULE_ANCHORCONTROL_TOGGLETAGMAP:
                return 'fa-map-marker';
        }

        return parent::getFontawesome($module, $props);
    }
    public function getHref(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_ANCHORCONTROL_TOGGLEMAP:
            case self::MODULE_ANCHORCONTROL_TOGGLEAUTHORMAP:
            case self::MODULE_ANCHORCONTROL_TOGGLETAGMAP:
                // Assume there is only one .collapse.map in this block
                // return '#'.$props['block-id'].' > .blocksection-inners .collapse.map';
                return $this->getProp($module, $props, 'target');
        }

        return parent::getHref($module, $props);
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        switch ($module[1]) {
            case self::MODULE_ANCHORCONTROL_TOGGLEMAP:
            case self::MODULE_ANCHORCONTROL_TOGGLEAUTHORMAP:
            case self::MODULE_ANCHORCONTROL_TOGGLETAGMAP:
                // It will add class "in" through .js if there is no cookie
                $this->addJsmethod($ret, 'cookieToggleClass');
                break;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_ANCHORCONTROL_TOGGLEMAP:
            case self::MODULE_ANCHORCONTROL_TOGGLEAUTHORMAP:
            case self::MODULE_ANCHORCONTROL_TOGGLETAGMAP:
                $this->appendProp($module, $props, 'class', 'pop-togglemap-btn');
                $this->appendProp($module, $props, 'class', 'btn btn-default btn-sm');
                $this->mergeProp(
                    $module,
                    $props,
                    'params',
                    array(
                        'data-toggle' => 'collapse',
                    )
                );
                // Set the params for the cookie
                $this->mergeProp(
                    $module,
                    $props,
                    'params',
                    array(
                        'data-cookieid' => \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleFullName($module).'-togglemap',
                        'data-cookietarget' => $this->getProp($module, $props, 'target')/*'#'.$props['block-id'].' > .blocksection-inners .collapse.map'*/,
                        'data-cookiecollapse' => 'show',
                        'data-togglecookiebtn' => 'self',
                    )
                );
                break;
        }

        switch ($module[1]) {
         // The map is initially toggled non-visible
            case self::MODULE_ANCHORCONTROL_TOGGLEAUTHORMAP:
            case self::MODULE_ANCHORCONTROL_TOGGLETAGMAP:
                $this->mergeProp(
                    $module,
                    $props,
                    'params',
                    array(
                        'data-initial' => 'set',
                    )
                );
                break;
        }

        parent::initModelProps($module, $props);
    }
}


