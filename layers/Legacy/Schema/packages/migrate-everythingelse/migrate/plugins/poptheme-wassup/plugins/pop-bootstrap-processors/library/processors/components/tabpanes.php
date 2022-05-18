<?php
use PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade;

/**
 * All PageSections
 */
class PoP_Module_Processor_TabPanes extends PoP_Module_Processor_TabPanelComponentsBase
{
    public final const COMPONENT_PAGESECTION_ADDONS = 'pagesection-addons';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_PAGESECTION_ADDONS],
        );
    }

    public function getSubcomponents(array $component): array
    {
        $ret = parent::getSubcomponents($component);

        $pop_component_componentroutingprocessor_manager = ComponentRoutingProcessorManagerFacade::getInstance();

        switch ($component[1]) {
            case self::COMPONENT_PAGESECTION_ADDONS:
                // If not told to be empty, then add the page submodule
                $componentAtts = count($component) >= 3 ? $component[2] : null;
                if (!($componentAtts && $componentAtts['empty'])) {
                    $ret[] = [PoP_Module_Processor_Pages::class, PoP_Module_Processor_Pages::COMPONENT_PAGE_ADDONS];
                }
                break;
        }

        return $ret;
    }

    public function getJsmethods(array $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_PAGESECTION_ADDONS:
                $this->addJsmethod($ret, 'scrollbarVertical');
                break;
        }

        return $ret;
    }

    protected function getContentClass(array $component, array &$props)
    {
        $ret = parent::getContentClass($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_PAGESECTION_ADDONS:
                $ret .= ' perfect-scrollbar-offsetreference';
                break;
        }

        return $ret;
    }

    public function getID(array $component, array &$props): string
    {
        switch ($component[1]) {
            case self::COMPONENT_PAGESECTION_ADDONS:
                return POP_COMPONENTID_PAGESECTIONCONTAINERID_ADDONS;
        }

        return parent::getID($component, $props);
    }

    public function getModelPropsForDescendantComponents(array $component, array &$props): array
    {
        $ret = parent::getModelPropsForDescendantComponents($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_PAGESECTION_ADDONS:
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

    public function initModelProps(array $component, array &$props): void
    {
        // The module must be at the head of the $props array passed to all `initModelProps`, so that function `getPathHeadModule` can work
        $componentFullName = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleFullName($component);
        $module_props = array(
            $componentFullName => &$props[$componentFullName],
        );
        switch ($component[1]) {
            case self::COMPONENT_PAGESECTION_ADDONS:
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
                    $component,
                    array(&$module_props),
                    $this
                );
                break;
        }

        parent::initModelProps($component, $props);
    }
}


