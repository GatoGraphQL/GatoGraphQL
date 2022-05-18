<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_BootstrapViewComponentsBase extends PoP_Module_Processor_BootstrapComponentsBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_BootstrapWebPlatform_TemplateResourceLoaderProcessor::class, PoP_BootstrapWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_BOOTSTRAPCOMPONENT_VIEWCOMPONENT];
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        return array_merge(
            parent::getSubComponentVariations($componentVariation),
            $this->getInnerSubmodules($componentVariation)
        );
    }

    public function getInnerSubmodules(array $componentVariation): array
    {
        return array();
    }

    public function getBootstrapcomponentType(array $componentVariation)
    {
        return $this->getType($componentVariation);
    }

    public function getType(array $componentVariation)
    {
        return '';
    }
    public function getViewcomponentClass(array $componentVariation)
    {
        return '';
    }
    public function getViewcomponentParams(array $componentVariation, array &$props)
    {
        $frontend_id = PoP_Bootstrap_Utils::getFrontendId($this->getFrontendId($componentVariation, $props), $this->getType($componentVariation));
        return array(
            'data-initjs-targets' =>  '#'.$frontend_id.'-container > div.pop-block'
        );
    }

    protected function getInitjsBlockbranches(array $componentVariation, array &$props)
    {
        $ret = parent::getInitjsBlockbranches($componentVariation, $props);
        
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        
        $frontend_id = PoP_Bootstrap_Utils::getFrontendId($this->getFrontendId($componentVariation, $props), $this->getType($componentVariation));
        $ret[] = '#'.$frontend_id.'-container > div.pop-block';

        return $ret;
    }

    public function getDialogClass(array $componentVariation)
    {
        return '';
    }
    public function getHeaderTitle(array $componentVariation)
    {
        return null;
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        if ($inner_componentVariations = $this->getInnerSubmodules($componentVariation)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['inners'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'], 
                $inner_componentVariations
            );
        }

        // Fill in all the properties
        $ret[GD_JS_CLASSES]['viewcomponent'] = $this->getViewcomponentClass($componentVariation);
        $ret['type'] = $this->getType($componentVariation);
        $ret[GD_JS_TITLES] = array();

        if ($dialog_class = $this->getDialogClass($componentVariation)) {
            $ret[GD_JS_CLASSES]['dialog'] = $dialog_class;
        }
        if ($header_title = $this->getHeaderTitle($componentVariation)) {
            $ret[GD_JS_TITLES]['header'] = $header_title;
        }
        
        return $ret;
    }

    public function addWebPlatformModuleConfiguration(&$ret, array $componentVariation, array &$props)
    {
        if ($viewcomponent_params = $this->getViewcomponentParams($componentVariation, $props)) {
            $ret['viewcomponent-params'] = $viewcomponent_params;
        }
    }
}
