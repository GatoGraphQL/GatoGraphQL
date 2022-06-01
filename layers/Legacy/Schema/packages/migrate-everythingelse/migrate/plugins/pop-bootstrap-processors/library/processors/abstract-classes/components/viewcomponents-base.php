<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_BootstrapViewComponentsBase extends PoP_Module_Processor_BootstrapComponentsBase
{
    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoP_BootstrapWebPlatform_TemplateResourceLoaderProcessor::class, PoP_BootstrapWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_BOOTSTRAPCOMPONENT_VIEWCOMPONENT];
    }

    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        return array_merge(
            parent::getSubcomponents($component),
            $this->getInnerSubcomponents($component)
        );
    }

    public function getInnerSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        return array();
    }

    public function getBootstrapcomponentType(\PoP\ComponentModel\Component\Component $component)
    {
        return $this->getType($component);
    }

    public function getType(\PoP\ComponentModel\Component\Component $component)
    {
        return '';
    }
    public function getViewcomponentClass(\PoP\ComponentModel\Component\Component $component)
    {
        return '';
    }
    public function getViewcomponentParams(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $frontend_id = PoP_Bootstrap_Utils::getFrontendId($this->getFrontendId($component, $props), $this->getType($component));
        return array(
            'data-initjs-targets' =>  '#'.$frontend_id.'-container > div.pop-block'
        );
    }

    protected function getInitjsBlockbranches(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getInitjsBlockbranches($component, $props);
        
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        
        $frontend_id = PoP_Bootstrap_Utils::getFrontendId($this->getFrontendId($component, $props), $this->getType($component));
        $ret[] = '#'.$frontend_id.'-container > div.pop-block';

        return $ret;
    }

    public function getDialogClass(\PoP\ComponentModel\Component\Component $component)
    {
        return '';
    }
    public function getHeaderTitle(\PoP\ComponentModel\Component\Component $component)
    {
        return null;
    }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        if ($inner_components = $this->getInnerSubcomponents($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['inners'] = array_map(
                \PoP\ComponentModel\Facades\Modules\ComponentHelpersFacade::getInstance()->getComponentOutputName(...), 
                $inner_components
            );
        }

        // Fill in all the properties
        $ret[GD_JS_CLASSES]['viewcomponent'] = $this->getViewcomponentClass($component);
        $ret['type'] = $this->getType($component);
        $ret[GD_JS_TITLES] = array();

        if ($dialog_class = $this->getDialogClass($component)) {
            $ret[GD_JS_CLASSES]['dialog'] = $dialog_class;
        }
        if ($header_title = $this->getHeaderTitle($component)) {
            $ret[GD_JS_TITLES]['header'] = $header_title;
        }
        
        return $ret;
    }

    public function addWebPlatformModuleConfiguration(&$ret, \PoP\ComponentModel\Component\Component $component, array &$props)
    {
        if ($viewcomponent_params = $this->getViewcomponentParams($component, $props)) {
            $ret['viewcomponent-params'] = $viewcomponent_params;
        }
    }
}
