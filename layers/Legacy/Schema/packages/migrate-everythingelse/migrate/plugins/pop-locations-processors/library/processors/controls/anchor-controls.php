<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Locations_Module_Processor_CustomAnchorControls extends PoP_Module_Processor_AnchorControlsBase
{
    public final const MODULE_ANCHORCONTROL_TOGGLEMAP = 'anchorcontrol-togglemap';
    public final const MODULE_ANCHORCONTROL_TOGGLEAUTHORMAP = 'anchorcontrol-toggleauthormap';
    public final const MODULE_ANCHORCONTROL_TOGGLETAGMAP = 'anchorcontrol-toggletagmap';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_ANCHORCONTROL_TOGGLEMAP],
            [self::class, self::MODULE_ANCHORCONTROL_TOGGLEAUTHORMAP],
            [self::class, self::MODULE_ANCHORCONTROL_TOGGLETAGMAP],
        );
    }

    public function getLabel(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_ANCHORCONTROL_TOGGLEMAP:
            case self::MODULE_ANCHORCONTROL_TOGGLEAUTHORMAP:
            case self::MODULE_ANCHORCONTROL_TOGGLETAGMAP:
                return TranslationAPIFacade::getInstance()->__('Toggle Map', 'poptheme-wassup');
        }

        return parent::getLabel($componentVariation, $props);
    }
    public function getFontawesome(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_ANCHORCONTROL_TOGGLEMAP:
            case self::MODULE_ANCHORCONTROL_TOGGLEAUTHORMAP:
            case self::MODULE_ANCHORCONTROL_TOGGLETAGMAP:
                return 'fa-map-marker';
        }

        return parent::getFontawesome($componentVariation, $props);
    }
    public function getHref(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_ANCHORCONTROL_TOGGLEMAP:
            case self::MODULE_ANCHORCONTROL_TOGGLEAUTHORMAP:
            case self::MODULE_ANCHORCONTROL_TOGGLETAGMAP:
                // Assume there is only one .collapse.map in this block
                // return '#'.$props['block-id'].' > .blocksection-inners .collapse.map';
                return $this->getProp($componentVariation, $props, 'target');
        }

        return parent::getHref($componentVariation, $props);
    }

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_ANCHORCONTROL_TOGGLEMAP:
            case self::MODULE_ANCHORCONTROL_TOGGLEAUTHORMAP:
            case self::MODULE_ANCHORCONTROL_TOGGLETAGMAP:
                // It will add class "in" through .js if there is no cookie
                $this->addJsmethod($ret, 'cookieToggleClass');
                break;
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_ANCHORCONTROL_TOGGLEMAP:
            case self::MODULE_ANCHORCONTROL_TOGGLEAUTHORMAP:
            case self::MODULE_ANCHORCONTROL_TOGGLETAGMAP:
                $this->appendProp($componentVariation, $props, 'class', 'pop-togglemap-btn');
                $this->appendProp($componentVariation, $props, 'class', 'btn btn-default btn-sm');
                $this->mergeProp(
                    $componentVariation,
                    $props,
                    'params',
                    array(
                        'data-toggle' => 'collapse',
                    )
                );
                // Set the params for the cookie
                $this->mergeProp(
                    $componentVariation,
                    $props,
                    'params',
                    array(
                        'data-cookieid' => \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleFullName($componentVariation).'-togglemap',
                        'data-cookietarget' => $this->getProp($componentVariation, $props, 'target')/*'#'.$props['block-id'].' > .blocksection-inners .collapse.map'*/,
                        'data-cookiecollapse' => 'show',
                        'data-togglecookiebtn' => 'self',
                    )
                );
                break;
        }

        switch ($componentVariation[1]) {
         // The map is initially toggled non-visible
            case self::MODULE_ANCHORCONTROL_TOGGLEAUTHORMAP:
            case self::MODULE_ANCHORCONTROL_TOGGLETAGMAP:
                $this->mergeProp(
                    $componentVariation,
                    $props,
                    'params',
                    array(
                        'data-initial' => 'set',
                    )
                );
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}


