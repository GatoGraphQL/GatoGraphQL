<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_Wassup_Module_Processor_AnchorControls extends PoP_Module_Processor_AnchorControlsBase
{
    public const MODULE_ANCHORCONTROL_TOGGLEQUICKVIEWINFO = 'anchorcontrol-togglequickviewinfo';
    public const MODULE_ANCHORCONTROL_TOGGLESIDEINFO = 'anchorcontrol-togglesideinfo';
    public const MODULE_ANCHORCONTROL_TOGGLESIDEINFOXS = 'anchorcontrol-togglesideinfoxs';
    public const MODULE_ANCHORCONTROL_TOGGLESIDEINFOXS_BACK = 'anchorcontrol-togglesideinfoxs-back';
    public const MODULE_ANCHORCONTROL_TOGGLETABS = 'anchorcontrol-toggletabs';
    public const MODULE_ANCHORCONTROL_TOGGLETABSXS = 'anchorcontrol-toggletabsxs';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_ANCHORCONTROL_TOGGLEQUICKVIEWINFO],
            [self::class, self::MODULE_ANCHORCONTROL_TOGGLESIDEINFO],
            [self::class, self::MODULE_ANCHORCONTROL_TOGGLESIDEINFOXS],
            [self::class, self::MODULE_ANCHORCONTROL_TOGGLESIDEINFOXS_BACK],
            [self::class, self::MODULE_ANCHORCONTROL_TOGGLETABS],
            [self::class, self::MODULE_ANCHORCONTROL_TOGGLETABSXS],
        );
    }

    public function getLabel(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_ANCHORCONTROL_TOGGLEQUICKVIEWINFO:
            case self::MODULE_ANCHORCONTROL_TOGGLESIDEINFO:
            case self::MODULE_ANCHORCONTROL_TOGGLESIDEINFOXS:
                return TranslationAPIFacade::getInstance()->__('Toggle sidebar', 'pop-coreprocessors');

            case self::MODULE_ANCHORCONTROL_TOGGLESIDEINFOXS_BACK:
                return TranslationAPIFacade::getInstance()->__('Go back', 'pop-coreprocessors');

            case self::MODULE_ANCHORCONTROL_TOGGLETABS:
            case self::MODULE_ANCHORCONTROL_TOGGLETABSXS:
                return TranslationAPIFacade::getInstance()->__('Toggle tabs', 'pop-coreprocessors');
        }

        return parent::getLabel($module, $props);
    }
    public function getText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_ANCHORCONTROL_TOGGLESIDEINFOXS_BACK:
                return null;
        }

        return parent::getText($module, $props);
    }
    public function getIcon(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_ANCHORCONTROL_TOGGLEQUICKVIEWINFO:
            case self::MODULE_ANCHORCONTROL_TOGGLESIDEINFO:
            case self::MODULE_ANCHORCONTROL_TOGGLESIDEINFOXS:
                return 'glyphicon-arrow-right';

            case self::MODULE_ANCHORCONTROL_TOGGLESIDEINFOXS_BACK:
                return 'glyphicon-arrow-left';

            case self::MODULE_ANCHORCONTROL_TOGGLETABS:
            case self::MODULE_ANCHORCONTROL_TOGGLETABSXS:
                return 'glyphicon-time';
        }

        return parent::getIcon($module);
    }

    public function initModelProps(array $module, array &$props): void
    {
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        switch ($module[1]) {
            case self::MODULE_ANCHORCONTROL_TOGGLEQUICKVIEWINFO:
                $this->mergeProp(
                    $module,
                    $props,
                    'params',
                    array(
                        'data-toggle' => 'offcanvas-toggle',
                        'data-target' => '#'.POP_MODULEID_PAGESECTIONCONTAINERID_QUICKVIEWSIDEINFO,
                    )
                );
                break;

            case self::MODULE_ANCHORCONTROL_TOGGLESIDEINFO:
            case self::MODULE_ANCHORCONTROL_TOGGLESIDEINFOXS:
            case self::MODULE_ANCHORCONTROL_TOGGLESIDEINFOXS_BACK:
                $mode = '';
                $classs = '';
                $xs = array(
                    [self::class, self::MODULE_ANCHORCONTROL_TOGGLESIDEINFOXS],
                    [self::class, self::MODULE_ANCHORCONTROL_TOGGLESIDEINFOXS_BACK],
                );
                $tablet = array(
                    [self::class, self::MODULE_ANCHORCONTROL_TOGGLESIDEINFO],
                );
                if (in_array($module, $xs)) {
                    $mode = 'xs';
                    $classs = 'hidden-sm hidden-md hidden-lg';
                } elseif (in_array($module, $tablet)) {
                    $classs = 'hidden-xs';
                }

                $back = array(
                    [self::class, self::MODULE_ANCHORCONTROL_TOGGLESIDEINFOXS_BACK],
                );
                if (in_array($module, $back)) {
                    $classs .= ' btn btn-lg btn-link';
                }

                $this->appendProp($module, $props, 'class', $classs);
                $this->mergeProp(
                    $module,
                    $props,
                    'params',
                    array(
                        'data-toggle' => 'offcanvas-toggle',
                        'data-target' => '#'.POP_MODULEID_PAGESECTIONCONTAINERID_BODYSIDEINFO,
                        'data-mode' => $mode,
                    )
                );
                break;

            case self::MODULE_ANCHORCONTROL_TOGGLETABS:
            case self::MODULE_ANCHORCONTROL_TOGGLETABSXS:
                $xs = array(
                    [self::class, self::MODULE_ANCHORCONTROL_TOGGLETABSXS],
                );
                if (in_array($module, $xs)) {
                    $mode = 'xs';
                    $classs = 'hidden-sm hidden-md hidden-lg';
                } else {
                    $mode = '';
                    $classs = 'hidden-xs';
                }
                $this->appendProp($module, $props, 'class', $classs);
                $this->mergeProp(
                    $module,
                    $props,
                    'params',
                    array(
                        'data-toggle' => 'offcanvas-toggle',
                        'data-target' => '#'.POP_MODULEID_PAGESECTIONCONTAINERID_BODYTABS,
                        'data-mode' => $mode,
                    )
                );
                break;
        }

        parent::initModelProps($module, $props);
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        switch ($module[1]) {
            case self::MODULE_ANCHORCONTROL_TOGGLEQUICKVIEWINFO:
            case self::MODULE_ANCHORCONTROL_TOGGLESIDEINFO:
            case self::MODULE_ANCHORCONTROL_TOGGLESIDEINFOXS:
            case self::MODULE_ANCHORCONTROL_TOGGLESIDEINFOXS_BACK:
            case self::MODULE_ANCHORCONTROL_TOGGLETABS:
            case self::MODULE_ANCHORCONTROL_TOGGLETABSXS:
                $this->addJsmethod($ret, 'offcanvasToggle');
                break;
        }
        return $ret;
    }
}


