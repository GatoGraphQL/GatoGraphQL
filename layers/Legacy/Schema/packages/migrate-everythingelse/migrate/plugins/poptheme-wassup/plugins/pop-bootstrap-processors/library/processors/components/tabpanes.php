<?php
use PoP\ComponentModel\Modules\ModuleUtils;
use PoP\ModuleRouting\Facades\RouteModuleProcessorManagerFacade;

/**
 * All PageSections
 */
class PoP_Module_Processor_TabPanes extends PoP_Module_Processor_TabPanelComponentsBase
{
    public const MODULE_PAGESECTION_ADDONS = 'pagesection-addons';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_PAGESECTION_ADDONS],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        $pop_module_routemoduleprocessor_manager = RouteModuleProcessorManagerFacade::getInstance();

        switch ($module[1]) {
            case self::MODULE_PAGESECTION_ADDONS:
                // If not told to be empty, then add the page submodule
                $moduleAtts = count($module) >= 3 ? $module[2] : null;
                if (!($moduleAtts && $moduleAtts['empty'])) {
                    $ret[] = [PoP_Module_Processor_Pages::class, PoP_Module_Processor_Pages::MODULE_PAGE_ADDONS];
                }
                break;
        }

        return $ret;
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        switch ($module[1]) {
            case self::MODULE_PAGESECTION_ADDONS:
                $this->addJsmethod($ret, 'scrollbarVertical');
                break;
        }

        return $ret;
    }

    protected function getContentClass(array $module, array &$props)
    {
        $ret = parent::getContentClass($module, $props);

        switch ($module[1]) {
            case self::MODULE_PAGESECTION_ADDONS:
                $ret .= ' perfect-scrollbar-offsetreference';
                break;
        }

        return $ret;
    }

    public function getID(array $module, array &$props): string
    {
        switch ($module[1]) {
            case self::MODULE_PAGESECTION_ADDONS:
                return POP_MODULEID_PAGESECTIONCONTAINERID_ADDONS;
        }

        return parent::getID($module, $props);
    }

    public function getModelPropsForDescendantModules(array $module, array &$props): array
    {
        $ret = parent::getModelPropsForDescendantModules($module, $props);

        switch ($module[1]) {
            case self::MODULE_PAGESECTION_ADDONS:
                // Override the style of all the form submit buttons
                $ret['btn-submit-class'] = 'btn btn-warning btn-block';

                // No style for the "Publish" button box when inside the Addons
                $ret['forminput-publish-class'] = 'publishbox';

                // Override the style of forms for the Addons (eg: ContentPost Create/Update)
                $ret['form-row-class'] = 'form-addons form-row row';
                $ret['form-left-class'] = 'form-addons form-leftside col-sm-8';
                $ret['form-right-class'] = 'form-addons form-rightside col-sm-4';

                // Make the widgets collapsible
                $ret['widget-collapsible'] = true;

                // Style of the widgets
                $ret['form-widget-class'] = 'panel panel-warning';

                // Editor rows
                $ret['editor-rows'] = 5;
                break;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        // The module must be at the head of the $props array passed to all `initModelProps`, so that function `getPathHeadModule` can work
        $moduleFullName = ModuleUtils::getModuleFullName($module);
        $module_props = array(
            $moduleFullName => &$props[$moduleFullName],
        );
        switch ($module[1]) {
            case self::MODULE_PAGESECTION_ADDONS:
                // // Override the style of all the form submit buttons
                // $this->add_general_prop($ret, 'btn-submit-class', 'btn btn-warning btn-block');

                // // No style for the "Publish" button box when inside the Addons
                // $this->add_general_prop($ret, 'forminput-publish-class', 'publishbox');

                // // Override the style of forms for the Addons (eg: ContentPost Create/Update)
                // $this->add_general_prop($ret, 'form-row-class', 'form-addons form-row row');
                // $this->add_general_prop($ret, 'form-left-class', 'form-addons form-leftside col-sm-8');
                // $this->add_general_prop($ret, 'form-right-class', 'form-addons form-rightside col-sm-4');

                // // Make the widgets collapsible
                // $this->add_general_prop($ret, 'widget-collapsible', true);

                // // Style of the widgets
                // $this->add_general_prop($ret, 'form-widget-class', 'panel panel-warning');

                // // Editor rows
                // $this->add_general_prop($ret, 'editor-rows', 5);

                \PoP\Root\App::doAction(
                    'PoP_Module_Processor_CustomTabPanePageSections:get_props_block_initial:addons',
                    $module,
                    array(&$module_props),
                    $this
                );
                break;
        }

        parent::initModelProps($module, $props);
    }
}


