<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;

abstract class PoP_Module_Processor_BootstrapViewComponentsBase extends PoP_Module_Processor_BootstrapComponentsBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_BootstrapWebPlatform_TemplateResourceLoaderProcessor::class, PoP_BootstrapWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_BOOTSTRAPCOMPONENT_VIEWCOMPONENT];
    }

    public function getSubmodules(array $module): array
    {
        return array_merge(
            parent::getSubmodules($module),
            $this->getInnerSubmodules($module)
        );
    }

    public function getInnerSubmodules(array $module): array
    {
        return array();
    }

    public function getBootstrapcomponentType(array $module)
    {
        return $this->getType($module);
    }

    public function getType(array $module)
    {
        return '';
    }
    public function getViewcomponentClass(array $module)
    {
        return '';
    }
    public function getViewcomponentParams(array $module, array &$props)
    {
        $frontend_id = PoP_Bootstrap_Utils::getFrontendId($this->getFrontendId($module, $props), $this->getType($module));
        return array(
            'data-initjs-targets' =>  '#'.$frontend_id.'-container > div.pop-block'
        );
    }

    protected function getInitjsBlockbranches(array $module, array &$props)
    {
        $ret = parent::getInitjsBlockbranches($module, $props);
        
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
        
        $frontend_id = PoP_Bootstrap_Utils::getFrontendId($this->getFrontendId($module, $props), $this->getType($module));
        $ret[] = '#'.$frontend_id.'-container > div.pop-block';

        return $ret;
    }

    public function getDialogClass(array $module)
    {
        return '';
    }
    public function getHeaderTitle(array $module)
    {
        return null;
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        if ($inner_modules = $this->getInnerSubmodules($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['inners'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'], 
                $inner_modules
            );
        }

        // Fill in all the properties
        $ret[GD_JS_CLASSES]['viewcomponent'] = $this->getViewcomponentClass($module);
        $ret['type'] = $this->getType($module);
        $ret[GD_JS_TITLES] = array();

        if ($dialog_class = $this->getDialogClass($module)) {
            $ret[GD_JS_CLASSES]['dialog'] = $dialog_class;
        }
        if ($header_title = $this->getHeaderTitle($module)) {
            $ret[GD_JS_TITLES]['header'] = $header_title;
        }
        
        return $ret;
    }

    public function addWebPlatformModuleConfiguration(&$ret, array $module, array &$props)
    {
        if ($viewcomponent_params = $this->getViewcomponentParams($module, $props)) {
            $ret['viewcomponent-params'] = $viewcomponent_params;
        }
    }
}
