<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\ComponentProcessors\FormattableModuleInterface;
use PoP\ComponentModel\State\ApplicationState;

trait FormatActiveTrait
{
    public function isSubcomponentActivePanel(\PoP\ComponentModel\Component\Component $component, $subcomponent)
    {
        return \PoP\Root\App::getState('format') == $this->getSubcomponentFormat($component, $subcomponent);
    }

    protected function getSubcomponentFormat(\PoP\ComponentModel\Component\Component $component, $subcomponent)
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        $processor = $componentprocessor_manager->getProcessor($subcomponent);
        if ($processor instanceof FormattableModuleInterface) {
            return $processor->getFormat($subcomponent);
        }
    
        return null;
    }

    protected function getDefaultActivepanelFormat(\PoP\ComponentModel\Component\Component $component)
    {
        return null;
    }

    public function getDefaultActivepanelSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        if ($default_format = $this->getDefaultActivepanelFormat($component)) {
            foreach ($this->getSubcomponents($component) as $subcomponent) {
                if ($default_format == $this->getSubcomponentFormat($component, $subcomponent)) {
                    return $subcomponent;
                }
            }
        }

        return parent::getDefaultActivepanelSubcomponent($component);
    }
}
