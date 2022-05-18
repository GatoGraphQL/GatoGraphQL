<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_WidgetsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_WIDGET];
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        if ($layouts = $this->getLayoutSubmodules($componentVariation)) {
            $ret = array_merge(
                $ret,
                $layouts
            );
        }
        if ($quicklinkgroup = $this->getQuicklinkgroupSubmodule($componentVariation)) {
            $ret[] = $quicklinkgroup;
        }

        return $ret;
    }

    public function getLayoutSubmodules(array $componentVariation)
    {
        return array();
    }

    // function getSidebarcomponentInner(array $componentVariation) {

    //     return null;
    // }



    public function getMenuTitle(array $componentVariation, array &$props)
    {
        return null;
    }
    public function getFontawesome(array $componentVariation, array &$props)
    {
        return null;
    }
    public function getWidgetClass(array $componentVariation, array &$props)
    {
        return 'panel panel-default';
    }
    public function getTitleWrapperClass(array $componentVariation, array &$props)
    {
        return 'panel-heading';
    }
    public function getTitleClass(array $componentVariation, array &$props)
    {
        return 'panel-title';
    }
    public function getBodyClass(array $componentVariation, array &$props)
    {

        // return 'panel-body';
        return 'list-group';
    }
    public function getItemWrapper(array $componentVariation, array &$props)
    {

        // return null;
        return 'list-group-item';
    }
    // function expand(array $componentVariation, array &$props) {

    //     return false;
    // }
    public function showHeader(array $componentVariation, array &$props)
    {
        return true;
    }
    public function getTitleHtmltag(array $componentVariation, array &$props)
    {
        return 'h4';
    }
    public function getQuicklinkgroupSubmodule(array $componentVariation)
    {
        return null;
    }
    public function collapsible(array $componentVariation, array &$props)
    {

        // By default, return the general att, so it can be set always collapsible inside addons pageSection
        // $collapsible = $this->get_general_prop($props, 'widget-collapsible');
        $collapsible = $this->getProp($componentVariation, $props, 'widget-collapsible');
        if (!is_null($collapsible)) {
            return $collapsible; // true or false
        }

        // Default value
        return false;
    }
    public function isCollapsibleOpen(array $componentVariation, array &$props)
    {

        // By default, return the general att, so it can be set always collapsible inside addons pageSection
        // $open = $this->get_general_prop($props, 'widget-collapsible-open');
        $open = $this->getProp($componentVariation, $props, 'widget-collapsible-open');
        if (!is_null($open)) {
            return $open; // true or false
        }

        // Default value
        return true;
    }
    public function getCollapselinkTitle(array $componentVariation, array &$props)
    {
        return '<i class="fa fa-fw fa-eye-slash"></i>';
    }
    public function getCollapselinkClass(array $componentVariation, array &$props)
    {
        return 'pull-right btn btn-link widget-collapselink';
    }

    // function getJsmethods(array $componentVariation, array &$props) {

    //     $ret = parent::getJsmethods($componentVariation, $props);

    //     if ($this->showHeader($componentVariation, $props)) {
    //         $this->addJsmethod($ret, 'smallScreenHideCollapse', 'collapse');
    //     }

    //     return $ret;
    // }

    public function initModelProps(array $componentVariation, array &$props): void
    {

        // $sidebarcomponent_inner = $this->getSidebarcomponentInner($componentVariation);
        // $this->add_settings_id($sidebarcomponent_inner, $props, 'sidebarcomponent-inner');

        $this->setProp($componentVariation, $props, 'menu-title', $this->getMenuTitle($componentVariation, $props));
        $this->setProp($componentVariation, $props, 'fontawesome', $this->getFontawesome($componentVariation, $props));
        $this->setProp($componentVariation, $props, 'widget-class', $this->getWidgetClass($componentVariation, $props));
        $this->setProp($componentVariation, $props, 'title-wrapper-class', $this->getTitleWrapperClass($componentVariation, $props));
        $this->setProp($componentVariation, $props, 'title-class', $this->getTitleClass($componentVariation, $props));
        $this->setProp($componentVariation, $props, 'body-class', $this->getBodyClass($componentVariation, $props));
        // $this->setProp($componentVariation, $props, 'expand', $this->expand($componentVariation, $props));
        $this->setProp($componentVariation, $props, 'show-header', $this->showHeader($componentVariation, $props));
        $this->setProp($componentVariation, $props, 'title-htmltag', $this->getTitleHtmltag($componentVariation, $props));
        $this->setProp($componentVariation, $props, 'collapsible', $this->collapsible($componentVariation, $props));
        $this->setProp($componentVariation, $props, 'collapsible-open', $this->isCollapsibleOpen($componentVariation, $props));
        $this->setProp($componentVariation, $props, 'collapselink-title', $this->getCollapselinkTitle($componentVariation, $props));
        $this->setProp($componentVariation, $props, 'collapselink-class', $this->getCollapselinkClass($componentVariation, $props));

        // $sidebarcomponent_inner = $this->getSidebarcomponentInner($componentVariation);
        // $this->appendProp($sidebarcomponent_inner, $props, 'class', $this->getItemWrapper($componentVariation, $props));
        if ($layouts = $this->getLayoutSubmodules($componentVariation)) {
            $itemwrapper_class = $this->getItemWrapper($componentVariation, $props);
            foreach ($layouts as $layout) {
                $this->appendProp($layout, $props, 'class', $itemwrapper_class);
            }
        }

        parent::initModelProps($componentVariation, $props);
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret['menu-title'] = $this->getProp($componentVariation, $props, 'menu-title');
        $ret[GD_JS_FONTAWESOME] = $this->getProp($componentVariation, $props, 'fontawesome');

        // $ret['widget-class'] = $this->getProp($componentVariation, $props, 'widget-class');
        $ret[GD_JS_CLASSES]['widget'] = $this->getProp($componentVariation, $props, 'widget-class');
        // $ret['title-class'] = $this->getProp($componentVariation, $props, 'title-class');
        $ret[GD_JS_CLASSES]['title'] = $this->getProp($componentVariation, $props, 'title-class');
        // $ret['body-class'] = $this->getProp($componentVariation, $props, 'body-class');
        $ret[GD_JS_CLASSES]['body'] = $this->getProp($componentVariation, $props, 'body-class');
        // $ret['title-wrapper-class'] = $this->getProp($componentVariation, $props, 'title-wrapper-class');
        $ret[GD_JS_CLASSES]['title-wrapper'] = $this->getProp($componentVariation, $props, 'title-wrapper-class');
        // $ret['expand'] = $this->getProp($componentVariation, $props, 'expand');
        $ret['show-header'] = $this->getProp($componentVariation, $props, 'show-header');
        $ret['title-htmltag'] = $this->getProp($componentVariation, $props, 'title-htmltag');
        if ($this->getProp($componentVariation, $props, 'collapsible')) {
            $collapsible_class = $this->getProp($componentVariation, $props, 'collapsible-open') ? 'in' : '';
            $ret['collapsible'] = array(
                'class' => $collapsible_class
            );
            $ret[GD_JS_TITLES]['collapse-link'] = $this->getProp($componentVariation, $props, 'collapselink-title');
            $ret[GD_JS_CLASSES]['collapse-link'] = $this->getProp($componentVariation, $props, 'collapselink-class');
        }

        if ($layouts = $this->getLayoutSubmodules($componentVariation)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['layouts'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $layouts
            );
        }
        if ($quicklinkgroup = $this->getQuicklinkgroupSubmodule($componentVariation)) {
            $ret[GD_JS_CLASSES]['quicklinkgroup'] = 'sidebarwidget-quicklinkgroup pull-right';
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['quicklinkgroup'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($quicklinkgroup);
        }

        return $ret;
    }
}
