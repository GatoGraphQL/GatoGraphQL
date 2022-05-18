<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_WidgetsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_WIDGET];
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

        if ($layouts = $this->getLayoutSubmodules($component)) {
            $ret = array_merge(
                $ret,
                $layouts
            );
        }
        if ($quicklinkgroup = $this->getQuicklinkgroupSubmodule($component)) {
            $ret[] = $quicklinkgroup;
        }

        return $ret;
    }

    public function getLayoutSubmodules(array $component)
    {
        return array();
    }

    // function getSidebarcomponentInner(array $component) {

    //     return null;
    // }



    public function getMenuTitle(array $component, array &$props)
    {
        return null;
    }
    public function getFontawesome(array $component, array &$props)
    {
        return null;
    }
    public function getWidgetClass(array $component, array &$props)
    {
        return 'panel panel-default';
    }
    public function getTitleWrapperClass(array $component, array &$props)
    {
        return 'panel-heading';
    }
    public function getTitleClass(array $component, array &$props)
    {
        return 'panel-title';
    }
    public function getBodyClass(array $component, array &$props)
    {

        // return 'panel-body';
        return 'list-group';
    }
    public function getItemWrapper(array $component, array &$props)
    {

        // return null;
        return 'list-group-item';
    }
    // function expand(array $component, array &$props) {

    //     return false;
    // }
    public function showHeader(array $component, array &$props)
    {
        return true;
    }
    public function getTitleHtmltag(array $component, array &$props)
    {
        return 'h4';
    }
    public function getQuicklinkgroupSubmodule(array $component)
    {
        return null;
    }
    public function collapsible(array $component, array &$props)
    {

        // By default, return the general att, so it can be set always collapsible inside addons pageSection
        // $collapsible = $this->get_general_prop($props, 'widget-collapsible');
        $collapsible = $this->getProp($component, $props, 'widget-collapsible');
        if (!is_null($collapsible)) {
            return $collapsible; // true or false
        }

        // Default value
        return false;
    }
    public function isCollapsibleOpen(array $component, array &$props)
    {

        // By default, return the general att, so it can be set always collapsible inside addons pageSection
        // $open = $this->get_general_prop($props, 'widget-collapsible-open');
        $open = $this->getProp($component, $props, 'widget-collapsible-open');
        if (!is_null($open)) {
            return $open; // true or false
        }

        // Default value
        return true;
    }
    public function getCollapselinkTitle(array $component, array &$props)
    {
        return '<i class="fa fa-fw fa-eye-slash"></i>';
    }
    public function getCollapselinkClass(array $component, array &$props)
    {
        return 'pull-right btn btn-link widget-collapselink';
    }

    // function getJsmethods(array $component, array &$props) {

    //     $ret = parent::getJsmethods($component, $props);

    //     if ($this->showHeader($component, $props)) {
    //         $this->addJsmethod($ret, 'smallScreenHideCollapse', 'collapse');
    //     }

    //     return $ret;
    // }

    public function initModelProps(array $component, array &$props): void
    {

        // $sidebarcomponent_inner = $this->getSidebarcomponentInner($component);
        // $this->add_settings_id($sidebarcomponent_inner, $props, 'sidebarcomponent-inner');

        $this->setProp($component, $props, 'menu-title', $this->getMenuTitle($component, $props));
        $this->setProp($component, $props, 'fontawesome', $this->getFontawesome($component, $props));
        $this->setProp($component, $props, 'widget-class', $this->getWidgetClass($component, $props));
        $this->setProp($component, $props, 'title-wrapper-class', $this->getTitleWrapperClass($component, $props));
        $this->setProp($component, $props, 'title-class', $this->getTitleClass($component, $props));
        $this->setProp($component, $props, 'body-class', $this->getBodyClass($component, $props));
        // $this->setProp($component, $props, 'expand', $this->expand($component, $props));
        $this->setProp($component, $props, 'show-header', $this->showHeader($component, $props));
        $this->setProp($component, $props, 'title-htmltag', $this->getTitleHtmltag($component, $props));
        $this->setProp($component, $props, 'collapsible', $this->collapsible($component, $props));
        $this->setProp($component, $props, 'collapsible-open', $this->isCollapsibleOpen($component, $props));
        $this->setProp($component, $props, 'collapselink-title', $this->getCollapselinkTitle($component, $props));
        $this->setProp($component, $props, 'collapselink-class', $this->getCollapselinkClass($component, $props));

        // $sidebarcomponent_inner = $this->getSidebarcomponentInner($component);
        // $this->appendProp($sidebarcomponent_inner, $props, 'class', $this->getItemWrapper($component, $props));
        if ($layouts = $this->getLayoutSubmodules($component)) {
            $itemwrapper_class = $this->getItemWrapper($component, $props);
            foreach ($layouts as $layout) {
                $this->appendProp($layout, $props, 'class', $itemwrapper_class);
            }
        }

        parent::initModelProps($component, $props);
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret['menu-title'] = $this->getProp($component, $props, 'menu-title');
        $ret[GD_JS_FONTAWESOME] = $this->getProp($component, $props, 'fontawesome');

        // $ret['widget-class'] = $this->getProp($component, $props, 'widget-class');
        $ret[GD_JS_CLASSES]['widget'] = $this->getProp($component, $props, 'widget-class');
        // $ret['title-class'] = $this->getProp($component, $props, 'title-class');
        $ret[GD_JS_CLASSES]['title'] = $this->getProp($component, $props, 'title-class');
        // $ret['body-class'] = $this->getProp($component, $props, 'body-class');
        $ret[GD_JS_CLASSES]['body'] = $this->getProp($component, $props, 'body-class');
        // $ret['title-wrapper-class'] = $this->getProp($component, $props, 'title-wrapper-class');
        $ret[GD_JS_CLASSES]['title-wrapper'] = $this->getProp($component, $props, 'title-wrapper-class');
        // $ret['expand'] = $this->getProp($component, $props, 'expand');
        $ret['show-header'] = $this->getProp($component, $props, 'show-header');
        $ret['title-htmltag'] = $this->getProp($component, $props, 'title-htmltag');
        if ($this->getProp($component, $props, 'collapsible')) {
            $collapsible_class = $this->getProp($component, $props, 'collapsible-open') ? 'in' : '';
            $ret['collapsible'] = array(
                'class' => $collapsible_class
            );
            $ret[GD_JS_TITLES]['collapse-link'] = $this->getProp($component, $props, 'collapselink-title');
            $ret[GD_JS_CLASSES]['collapse-link'] = $this->getProp($component, $props, 'collapselink-class');
        }

        if ($layouts = $this->getLayoutSubmodules($component)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['layouts'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $layouts
            );
        }
        if ($quicklinkgroup = $this->getQuicklinkgroupSubmodule($component)) {
            $ret[GD_JS_CLASSES]['quicklinkgroup'] = 'sidebarwidget-quicklinkgroup pull-right';
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['quicklinkgroup'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($quicklinkgroup);
        }

        return $ret;
    }
}
