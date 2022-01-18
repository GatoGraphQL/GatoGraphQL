<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;

abstract class PoP_Module_Processor_WidgetsBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_WIDGET];
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        if ($layouts = $this->getLayoutSubmodules($module)) {
            $ret = array_merge(
                $ret,
                $layouts
            );
        }
        if ($quicklinkgroup = $this->getQuicklinkgroupSubmodule($module)) {
            $ret[] = $quicklinkgroup;
        }

        return $ret;
    }

    public function getLayoutSubmodules(array $module)
    {
        return array();
    }

    // function getSidebarcomponentInner(array $module) {

    //     return null;
    // }



    public function getMenuTitle(array $module, array &$props)
    {
        return null;
    }
    public function getFontawesome(array $module, array &$props)
    {
        return null;
    }
    public function getWidgetClass(array $module, array &$props)
    {
        return 'panel panel-default';
    }
    public function getTitleWrapperClass(array $module, array &$props)
    {
        return 'panel-heading';
    }
    public function getTitleClass(array $module, array &$props)
    {
        return 'panel-title';
    }
    public function getBodyClass(array $module, array &$props)
    {

        // return 'panel-body';
        return 'list-group';
    }
    public function getItemWrapper(array $module, array &$props)
    {

        // return null;
        return 'list-group-item';
    }
    // function expand(array $module, array &$props) {

    //     return false;
    // }
    public function showHeader(array $module, array &$props)
    {
        return true;
    }
    public function getTitleHtmltag(array $module, array &$props)
    {
        return 'h4';
    }
    public function getQuicklinkgroupSubmodule(array $module)
    {
        return null;
    }
    public function collapsible(array $module, array &$props)
    {

        // By default, return the general att, so it can be set always collapsible inside addons pageSection
        // $collapsible = $this->get_general_prop($props, 'widget-collapsible');
        $collapsible = $this->getProp($module, $props, 'widget-collapsible');
        if (!is_null($collapsible)) {
            return $collapsible; // true or false
        }

        // Default value
        return false;
    }
    public function isCollapsibleOpen(array $module, array &$props)
    {

        // By default, return the general att, so it can be set always collapsible inside addons pageSection
        // $open = $this->get_general_prop($props, 'widget-collapsible-open');
        $open = $this->getProp($module, $props, 'widget-collapsible-open');
        if (!is_null($open)) {
            return $open; // true or false
        }

        // Default value
        return true;
    }
    public function getCollapselinkTitle(array $module, array &$props)
    {
        return '<i class="fa fa-fw fa-eye-slash"></i>';
    }
    public function getCollapselinkClass(array $module, array &$props)
    {
        return 'pull-right btn btn-link widget-collapselink';
    }

    // function getJsmethods(array $module, array &$props) {

    //     $ret = parent::getJsmethods($module, $props);

    //     if ($this->showHeader($module, $props)) {
    //         $this->addJsmethod($ret, 'smallScreenHideCollapse', 'collapse');
    //     }

    //     return $ret;
    // }

    public function initModelProps(array $module, array &$props): void
    {

        // $sidebarcomponent_inner = $this->getSidebarcomponentInner($module);
        // $this->add_settings_id($sidebarcomponent_inner, $props, 'sidebarcomponent-inner');

        $this->setProp($module, $props, 'menu-title', $this->getMenuTitle($module, $props));
        $this->setProp($module, $props, 'fontawesome', $this->getFontawesome($module, $props));
        $this->setProp($module, $props, 'widget-class', $this->getWidgetClass($module, $props));
        $this->setProp($module, $props, 'title-wrapper-class', $this->getTitleWrapperClass($module, $props));
        $this->setProp($module, $props, 'title-class', $this->getTitleClass($module, $props));
        $this->setProp($module, $props, 'body-class', $this->getBodyClass($module, $props));
        // $this->setProp($module, $props, 'expand', $this->expand($module, $props));
        $this->setProp($module, $props, 'show-header', $this->showHeader($module, $props));
        $this->setProp($module, $props, 'title-htmltag', $this->getTitleHtmltag($module, $props));
        $this->setProp($module, $props, 'collapsible', $this->collapsible($module, $props));
        $this->setProp($module, $props, 'collapsible-open', $this->isCollapsibleOpen($module, $props));
        $this->setProp($module, $props, 'collapselink-title', $this->getCollapselinkTitle($module, $props));
        $this->setProp($module, $props, 'collapselink-class', $this->getCollapselinkClass($module, $props));

        // $sidebarcomponent_inner = $this->getSidebarcomponentInner($module);
        // $this->appendProp($sidebarcomponent_inner, $props, 'class', $this->getItemWrapper($module, $props));
        if ($layouts = $this->getLayoutSubmodules($module)) {
            $itemwrapper_class = $this->getItemWrapper($module, $props);
            foreach ($layouts as $layout) {
                $this->appendProp($layout, $props, 'class', $itemwrapper_class);
            }
        }

        parent::initModelProps($module, $props);
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        $ret['menu-title'] = $this->getProp($module, $props, 'menu-title');
        $ret[GD_JS_FONTAWESOME] = $this->getProp($module, $props, 'fontawesome');

        // $ret['widget-class'] = $this->getProp($module, $props, 'widget-class');
        $ret[GD_JS_CLASSES]['widget'] = $this->getProp($module, $props, 'widget-class');
        // $ret['title-class'] = $this->getProp($module, $props, 'title-class');
        $ret[GD_JS_CLASSES]['title'] = $this->getProp($module, $props, 'title-class');
        // $ret['body-class'] = $this->getProp($module, $props, 'body-class');
        $ret[GD_JS_CLASSES]['body'] = $this->getProp($module, $props, 'body-class');
        // $ret['title-wrapper-class'] = $this->getProp($module, $props, 'title-wrapper-class');
        $ret[GD_JS_CLASSES]['title-wrapper'] = $this->getProp($module, $props, 'title-wrapper-class');
        // $ret['expand'] = $this->getProp($module, $props, 'expand');
        $ret['show-header'] = $this->getProp($module, $props, 'show-header');
        $ret['title-htmltag'] = $this->getProp($module, $props, 'title-htmltag');
        if ($this->getProp($module, $props, 'collapsible')) {
            $collapsible_class = $this->getProp($module, $props, 'collapsible-open') ? 'in' : '';
            $ret['collapsible'] = array(
                'class' => $collapsible_class
            );
            $ret[GD_JS_TITLES]['collapse-link'] = $this->getProp($module, $props, 'collapselink-title');
            $ret[GD_JS_CLASSES]['collapse-link'] = $this->getProp($module, $props, 'collapselink-class');
        }

        if ($layouts = $this->getLayoutSubmodules($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['layouts'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $layouts
            );
        }
        if ($quicklinkgroup = $this->getQuicklinkgroupSubmodule($module)) {
            $ret[GD_JS_CLASSES]['quicklinkgroup'] = 'sidebarwidget-quicklinkgroup pull-right';
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['quicklinkgroup'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($quicklinkgroup);
        }

        return $ret;
    }
}
