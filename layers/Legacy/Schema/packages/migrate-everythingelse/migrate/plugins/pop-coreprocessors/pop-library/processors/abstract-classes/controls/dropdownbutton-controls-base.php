<?php

abstract class PoP_Module_Processor_DropdownButtonControlsBase extends PoP_Module_Processor_ControlsBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_CONTROL_DROPDOWNBUTTON];
    }
    public function getBtnClass(array $component)
    {
        return 'btn btn-default';
    }
    
    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        if ($class = $this->getBtnClass($component)) {
            // $ret['btn-class'] = $class;
            $ret[GD_JS_CLASSES]['btn'] = $class;
        }

        if ($subComponents = $this->getSubcomponents($component)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['elements'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'], 
                $subComponents
            );
        }
        
        return $ret;
    }
}
