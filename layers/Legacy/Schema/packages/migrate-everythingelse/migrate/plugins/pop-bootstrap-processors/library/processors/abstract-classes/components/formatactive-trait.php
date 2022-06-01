<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\ComponentProcessors\FormattableModuleInterface;
use PoP\ComponentModel\State\ApplicationState;

trait FormatActiveTrait
{
    public function isSubcomponentActivePanel(\PoP\ComponentModel\Component\Component $component, $subComponent)
    {
        return \PoP\Root\App::getState('format') == $this->getSubcomponentFormat($component, $subComponent);
    }

    protected function getSubcomponentFormat(\PoP\ComponentModel\Component\Component $component, $subComponent)
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        $processor = $componentprocessor_manager->getProcessor($subComponent);
        if ($processor instanceof FormattableModuleInterface) {
            return $processor->getFormat($subComponent);
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
            foreach ($this->getSubcomponents($component) as $subComponent) {
                if ($default_format == $this->getSubcomponentFormat($component, $subComponent)) {
                    return $subComponent;
                }
            }
        }

        return parent::getDefaultActivepanelSubcomponent($component);
    }
}
