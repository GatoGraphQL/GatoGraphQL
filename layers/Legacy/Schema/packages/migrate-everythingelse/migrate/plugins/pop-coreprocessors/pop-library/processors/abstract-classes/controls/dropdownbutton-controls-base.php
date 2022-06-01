<?php

abstract class PoP_Module_Processor_DropdownButtonControlsBase extends PoP_Module_Processor_ControlsBase
{
    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_CONTROL_DROPDOWNBUTTON];
    }
    public function getBtnClass(\PoP\ComponentModel\Component\Component $component)
    {
        return 'btn btn-default';
    }
    
    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        if ($class = $this->getBtnClass($component)) {
            // $ret['btn-class'] = $class;
            $ret[GD_JS_CLASSES]['btn'] = $class;
        }

        if ($subcomponents = $this->getSubcomponents($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['elements'] = array_map(
                \PoP\ComponentModel\Facades\Modules\ComponentHelpersFacade::getInstance()->getComponentOutputName(...), 
                $subcomponents
            );
        }
        
        return $ret;
    }
}
