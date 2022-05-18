<?php
use PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade;

/**
 * All PageSections
 */
class PoP_Module_Processor_TabPanes extends PoP_Module_Processor_TabPanelComponentsBase
{
    public final const MODULE_PAGESECTION_ADDONS = 'pagesection-addons';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_PAGESECTION_ADDONS],
        );
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        $pop_module_componentroutingprocessor_manager = ComponentRoutingProcessorManagerFacade::getInstance();

        switch ($componentVariation[1]) {
            case self::MODULE_PAGESECTION_ADDONS:
                // If not told to be empty, then add the page submodule
                $moduleAtts = count($componentVariation) >= 3 ? $componentVariation[2] : null;
                if (!($moduleAtts && $moduleAtts['empty'])) {
                    $ret[] = [PoP_Module_Processor_Pages::class, PoP_Module_Processor_Pages::MODULE_PAGE_ADDONS];
                }
                break;
        }

        return $ret;
    }

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_PAGESECTION_ADDONS:
                $this->addJsmethod($ret, 'scrollbarVertical');
                break;
        }

        return $ret;
    }

    protected function getContentClass(array $componentVariation, array &$props)
    {
        $ret = parent::getContentClass($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_PAGESECTION_ADDONS:
                $ret .= ' perfect-scrollbar-offsetreference';
                break;
        }

        return $ret;
    }

    public function getID(array $componentVariation, array &$props): string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_PAGESECTION_ADDONS:
                return POP_MODULEID_PAGESECTIONCONTAINERID_ADDONS;
        }

        return parent::getID($componentVariation, $props);
    }

    public function getModelPropsForDescendantComponentVariations(array $componentVariation, array &$props): array
    {
        $ret = parent::getModelPropsForDescendantComponentVariations($componentVariation, $props);

        switch ($componentVariation[1]) {
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

    public function initModelProps(array $componentVariation, array &$props): void
    {
        // The module must be at the head of the $props array passed to all `initModelProps`, so that function `getPathHeadModule` can work
        $moduleFullName = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleFullName($componentVariation);
        $module_props = array(
            $moduleFullName => &$props[$moduleFullName],
        );
        switch ($componentVariation[1]) {
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
                    $componentVariation,
                    array(&$module_props),
                    $this
                );
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}


