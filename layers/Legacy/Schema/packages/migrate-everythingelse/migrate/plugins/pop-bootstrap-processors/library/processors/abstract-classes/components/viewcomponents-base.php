<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_BootstrapViewComponentsBase extends PoP_Module_Processor_BootstrapComponentsBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_BootstrapWebPlatform_TemplateResourceLoaderProcessor::class, PoP_BootstrapWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_BOOTSTRAPCOMPONENT_VIEWCOMPONENT];
    }

    public function getSubcomponents(array $component): array
    {
        return array_merge(
            parent::getSubcomponents($component),
            $this->getInnerSubcomponents($component)
        );
    }

    public function getInnerSubcomponents(array $component): array
    {
        return array();
    }

    public function getBootstrapcomponentType(array $component)
    {
        return $this->getType($component);
    }

    public function getType(array $component)
    {
        return '';
    }
    public function getViewcomponentClass(array $component)
    {
        return '';
    }
    public function getViewcomponentParams(array $component, array &$props)
    {
        $frontend_id = PoP_Bootstrap_Utils::getFrontendId($this->getFrontendId($component, $props), $this->getType($component));
        return array(
            'data-initjs-targets' =>  '#'.$frontend_id.'-container > div.pop-block'
        );
    }

    protected function getInitjsBlockbranches(array $component, array &$props)
    {
        $ret = parent::getInitjsBlockbranches($component, $props);
        
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        
        $frontend_id = PoP_Bootstrap_Utils::getFrontendId($this->getFrontendId($component, $props), $this->getType($component));
        $ret[] = '#'.$frontend_id.'-container > div.pop-block';

        return $ret;
    }

    public function getDialogClass(array $component)
    {
        return '';
    }
    public function getHeaderTitle(array $component)
    {
        return null;
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        if ($inner_components = $this->getInnerSubcomponents($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['inners'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'], 
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

    public function addWebPlatformModuleConfiguration(&$ret, array $component, array &$props)
    {
        if ($viewcomponent_params = $this->getViewcomponentParams($component, $props)) {
            $ret['viewcomponent-params'] = $viewcomponent_params;
        }
    }
}
