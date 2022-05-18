<?php

abstract class PoP_Module_Processor_DropdownButtonControlsBase extends PoP_Module_Processor_ControlsBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_CONTROL_DROPDOWNBUTTON];
    }
    public function getBtnClass(array $componentVariation)
    {
        return 'btn btn-default';
    }
    
    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        if ($class = $this->getBtnClass($componentVariation)) {
            // $ret['btn-class'] = $class;
            $ret[GD_JS_CLASSES]['btn'] = $class;
        }

        if ($subComponentVariations = $this->getSubComponentVariations($componentVariation)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['elements'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'], 
                $subComponentVariations
            );
        }
        
        return $ret;
    }
}
