<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_WidgetsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_WIDGET];
    }

    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);

        if ($layouts = $this->getLayoutSubcomponents($component)) {
            $ret = array_merge(
                $ret,
                $layouts
            );
        }
        if ($quicklinkgroup = $this->getQuicklinkgroupSubcomponent($component)) {
            $ret[] = $quicklinkgroup;
        }

        return $ret;
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        return array();
    }

    // function getSidebarcomponentInner(\PoP\ComponentModel\Component\Component $component) {

    //     return null;
    // }



    public function getMenuTitle(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return null;
    }
    public function getFontawesome(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return null;
    }
    public function getWidgetClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return 'panel panel-default';
    }
    public function getTitleWrapperClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return 'panel-heading';
    }
    public function getTitleClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return 'panel-title';
    }
    public function getBodyClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {

        // return 'panel-body';
        return 'list-group';
    }
    public function getItemWrapper(\PoP\ComponentModel\Component\Component $component, array &$props)
    {

        // return null;
        return 'list-group-item';
    }
    // function expand(\PoP\ComponentModel\Component\Component $component, array &$props) {

    //     return false;
    // }
    public function showHeader(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return true;
    }
    public function getTitleHtmltag(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return 'h4';
    }
    public function getQuicklinkgroupSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        return null;
    }
    public function collapsible(\PoP\ComponentModel\Component\Component $component, array &$props)
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
    public function isCollapsibleOpen(\PoP\ComponentModel\Component\Component $component, array &$props)
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
    public function getCollapselinkTitle(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return '<i class="fa fa-fw fa-eye-slash"></i>';
    }
    public function getCollapselinkClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return 'pull-right btn btn-link widget-collapselink';
    }

    // function getJsmethods(\PoP\ComponentModel\Component\Component $component, array &$props) {

    //     $ret = parent::getJsmethods($component, $props);

    //     if ($this->showHeader($component, $props)) {
    //         $this->addJsmethod($ret, 'smallScreenHideCollapse', 'collapse');
    //     }

    //     return $ret;
    // }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
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
        if ($layouts = $this->getLayoutSubcomponents($component)) {
            $itemwrapper_class = $this->getItemWrapper($component, $props);
            foreach ($layouts as $layout) {
                $this->appendProp($layout, $props, 'class', $itemwrapper_class);
            }
        }

        parent::initModelProps($component, $props);
    }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
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

        if ($layouts = $this->getLayoutSubcomponents($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['layouts'] = array_map(
                \PoP\ComponentModel\Facades\ComponentHelpers\ComponentHelpersFacade::getInstance()->getComponentOutputName(...),
                $layouts
            );
        }
        if ($quicklinkgroup = $this->getQuicklinkgroupSubcomponent($component)) {
            $ret[GD_JS_CLASSES]['quicklinkgroup'] = 'sidebarwidget-quicklinkgroup pull-right';
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['quicklinkgroup'] = \PoP\ComponentModel\Facades\ComponentHelpers\ComponentHelpersFacade::getInstance()->getComponentOutputName($quicklinkgroup);
        }

        return $ret;
    }
}
