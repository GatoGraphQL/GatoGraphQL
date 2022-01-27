<?php
use PoP\ComponentModel\Facades\HelperServices\RequestHelperServiceFacade;
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_AnchorControls extends PoP_Module_Processor_AnchorControlsBase
{
    public const MODULE_ANCHORCONTROL_TOGGLEOPTIONALFIELDS = 'anchorcontrol-toggleoptionalfields';
    public const MODULE_ANCHORCONTROL_EXPANDCOLLAPSIBLE = 'anchorcontrol-expandcollapsible';
    public const MODULE_ANCHORCONTROL_FILTERTOGGLE = 'anchorcontrol-filtertoggle';
    public const MODULE_ANCHORCONTROL_CURRENTURL = 'anchorcontrol-currenturl';
    public const MODULE_ANCHORCONTROL_SUBMENUTOGGLE_XS = 'anchorcontrol-submenutoggle-xs';
    public const MODULE_ANCHORCONTROL_PRINT = 'anchorcontrol-print';
    public const MODULE_ANCHORCONTROL_CLOSEPAGE = 'anchorcontrol-closepage';
    public const MODULE_ANCHORCONTROL_CLOSEPAGEBTN = 'anchorcontrol-closepagebtn';
    public const MODULE_ANCHORCONTROL_CLOSEPAGEBTNBIG = 'anchorcontrol-closepagebtnbig';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_ANCHORCONTROL_TOGGLEOPTIONALFIELDS],
            [self::class, self::MODULE_ANCHORCONTROL_EXPANDCOLLAPSIBLE],
            [self::class, self::MODULE_ANCHORCONTROL_FILTERTOGGLE],
            [self::class, self::MODULE_ANCHORCONTROL_CURRENTURL],
            [self::class, self::MODULE_ANCHORCONTROL_SUBMENUTOGGLE_XS],
            [self::class, self::MODULE_ANCHORCONTROL_PRINT],
            [self::class, self::MODULE_ANCHORCONTROL_CLOSEPAGE],
            [self::class, self::MODULE_ANCHORCONTROL_CLOSEPAGEBTN],
            [self::class, self::MODULE_ANCHORCONTROL_CLOSEPAGEBTNBIG],
        );
    }

    public function getLabel(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_ANCHORCONTROL_TOGGLEOPTIONALFIELDS:
                return TranslationAPIFacade::getInstance()->__('Toggle optional fields', 'pop-coreprocessors');

            case self::MODULE_ANCHORCONTROL_EXPANDCOLLAPSIBLE:
                return TranslationAPIFacade::getInstance()->__('Expand', 'pop-coreprocessors');

            case self::MODULE_ANCHORCONTROL_FILTERTOGGLE:
                return TranslationAPIFacade::getInstance()->__('Filter', 'pop-coreprocessors');

            case self::MODULE_ANCHORCONTROL_CURRENTURL:
                return TranslationAPIFacade::getInstance()->__('Permalink', 'pop-coreprocessors');

            case self::MODULE_ANCHORCONTROL_PRINT:
                return TranslationAPIFacade::getInstance()->__('Print', 'pop-coreprocessors');

            case self::MODULE_ANCHORCONTROL_CLOSEPAGE:
            case self::MODULE_ANCHORCONTROL_CLOSEPAGEBTN:
            case self::MODULE_ANCHORCONTROL_CLOSEPAGEBTNBIG:
                return TranslationAPIFacade::getInstance()->__('Close', 'pop-coreprocessors');
        }

        return parent::getLabel($module, $props);
    }
    public function getText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_ANCHORCONTROL_FILTERTOGGLE:
            case self::MODULE_ANCHORCONTROL_CLOSEPAGEBTN:
                return null;
        }

        return parent::getText($module, $props);
    }
    public function getIcon(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_ANCHORCONTROL_SUBMENUTOGGLE_XS:
                return 'glyphicon-menu-hamburger';

            case self::MODULE_ANCHORCONTROL_PRINT:
                return 'glyphicon-print';

            case self::MODULE_ANCHORCONTROL_CLOSEPAGE:
            case self::MODULE_ANCHORCONTROL_CLOSEPAGEBTN:
            case self::MODULE_ANCHORCONTROL_CLOSEPAGEBTNBIG:
                return 'glyphicon-remove';
        }

        return parent::getIcon($module);
    }
    public function getFontawesome(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_ANCHORCONTROL_TOGGLEOPTIONALFIELDS:
                return 'fa-star-half-o';

            case self::MODULE_ANCHORCONTROL_EXPANDCOLLAPSIBLE:
                return 'fa-arrows-v';

            case self::MODULE_ANCHORCONTROL_FILTERTOGGLE:
                return 'fa-filter';

            case self::MODULE_ANCHORCONTROL_CURRENTURL:
                return 'fa-link';
        }

        return parent::getFontawesome($module, $props);
    }
    public function getHref(array $module, array &$props)
    {
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        switch ($module[1]) {
            case self::MODULE_ANCHORCONTROL_TOGGLEOPTIONALFIELDS:
            case self::MODULE_ANCHORCONTROL_EXPANDCOLLAPSIBLE:
                // $block_id = $props['block-id'];
                // return '#'.$block_id.' .collapse';
                return $this->getProp($module, $props, 'target');

            case self::MODULE_ANCHORCONTROL_FILTERTOGGLE:
                // If PoP Engine Web Platform is not defined, then there is no `getFrontendId`
                if (defined('POP_ENGINEWEBPLATFORM_INITIALIZED')) {
                     // Comment Leo: calling ->getFrontendId( will possibly not work inside of a replicated pageSection, since this ID is hardcoded in the settings
                    // however the ID of the target will change. To deal with this, possibly use a jQuery selector other than $('#').
                    // Currently this is not a problem because we don't have any pageSection to be replicated that needs a filter (nothing listing content, only to create, eg: Add Project)

                    // Comment Leo 26/03/2019: This must be re-implemented through Handlebars function `upcomingModuleId`
                    // // The Filter is set in the props
                    // if ($filter = $this->getProp($module, $props, 'filter-module')) {
                    //     $filter_id = $moduleprocessor_manager->getProcessor($filter)->getFrontendId($filter, $props);
                    //     return '#'.$filter_id;
                    // }
                    return '#'; // This must be replaced! Not working now!
                }
                return null;

            case self::MODULE_ANCHORCONTROL_CURRENTURL:
                $requestHelperService = RequestHelperServiceFacade::getInstance();
                return $requestHelperService->getCurrentURL();

            case self::MODULE_ANCHORCONTROL_SUBMENUTOGGLE_XS:
                // If PoP Engine Web Platform is not defined, then there is no `getFrontendId`
                if (defined('POP_ENGINEWEBPLATFORM_INITIALIZED')) {
                     // The Submenu target is set in the props by the block
                    return $this->getProp($module, $props, 'submenu-target');
                }
                return null;
        }

        return parent::getHref($module, $props);
    }

    public function getTarget(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_ANCHORCONTROL_CURRENTURL:
                return \PoP\ConfigurationComponentModel\Constants\Targets::MAIN;
        }

        return parent::getTarget($module, $props);
    }

    public function initModelProps(array $module, array &$props): void
    {
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        switch ($module[1]) {
            case self::MODULE_ANCHORCONTROL_TOGGLEOPTIONALFIELDS:
                $this->appendProp($module, $props, 'class', 'btn btn-primary');
                $this->mergeProp(
                    $module,
                    $props,
                    'params',
                    array(
                        'data-toggle' => 'collapse'
                    )
                );
                break;

            case self::MODULE_ANCHORCONTROL_EXPANDCOLLAPSIBLE:
            case self::MODULE_ANCHORCONTROL_FILTERTOGGLE:
            case self::MODULE_ANCHORCONTROL_SUBMENUTOGGLE_XS:
                $this->appendProp($module, $props, 'class', 'btn btn-compact btn-link');
                $this->mergeProp(
                    $module,
                    $props,
                    'params',
                    array(
                        'data-toggle' => 'collapse'
                    )
                );
                break;

            case self::MODULE_ANCHORCONTROL_PRINT:
                $this->mergeProp(
                    $module,
                    $props,
                    'params',
                    array(
                        'data-blocktarget' => $this->getProp($module, $props, 'control-target')
                    )
                );
                break;
        }

        switch ($module[1]) {
            case self::MODULE_ANCHORCONTROL_CURRENTURL:
                $this->appendProp($module, $props, 'class', 'btn btn-compact btn-link');
                break;

            case self::MODULE_ANCHORCONTROL_CLOSEPAGEBTNBIG:
                $this->appendProp($module, $props, 'class', 'close');
                break;

            case self::MODULE_ANCHORCONTROL_CLOSEPAGEBTN:
                $this->appendProp($module, $props, 'class', 'btn btn-link');
                break;
        }

        parent::initModelProps($module, $props);
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        switch ($module[1]) {
            case self::MODULE_ANCHORCONTROL_PRINT:
                $this->addJsmethod($ret, 'controlPrint');
                break;

            case self::MODULE_ANCHORCONTROL_CLOSEPAGE:
            case self::MODULE_ANCHORCONTROL_CLOSEPAGEBTN:
            case self::MODULE_ANCHORCONTROL_CLOSEPAGEBTNBIG:
                $this->addJsmethod($ret, 'closePageTab');
                break;
        }
        return $ret;
    }
}


