<?php

abstract class PoP_Module_Processor_DropdownButtonControlsBase extends PoP_Module_Processor_ControlsBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_CONTROL_DROPDOWNBUTTON];
    }
    public function getBtnClass(array $module)
    {
        return 'btn btn-default';
    }
    
    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        if ($class = $this->getBtnClass($module)) {
            // $ret['btn-class'] = $class;
            $ret[GD_JS_CLASSES]['btn'] = $class;
        }

        if ($submodules = $this->getSubmodules($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['elements'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'], 
                $submodules
            );
        }
        
        return $ret;
    }
}
