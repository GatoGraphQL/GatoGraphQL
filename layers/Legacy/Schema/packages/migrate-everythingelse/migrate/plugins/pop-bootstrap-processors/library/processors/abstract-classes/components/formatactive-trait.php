<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\ComponentProcessors\FormattableModuleInterface;
use PoP\ComponentModel\State\ApplicationState;

trait FormatActiveTrait
{
    public function isSubmoduleActivePanel(array $component, $subComponent)
    {
        return \PoP\Root\App::getState('format') == $this->getSubmoduleFormat($component, $subComponent);
    }

    protected function getSubmoduleFormat(array $component, $subComponent)
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        $processor = $componentprocessor_manager->getProcessor($subComponent);
        if ($processor instanceof FormattableModuleInterface) {
            return $processor->getFormat($subComponent);
        }
    
        return null;
    }

    protected function getDefaultActivepanelFormat(array $component)
    {
        return null;
    }

    public function getDefaultActivepanelSubmodule(array $component)
    {
        if ($default_format = $this->getDefaultActivepanelFormat($component)) {
            foreach ($this->getSubComponents($component) as $subComponent) {
                if ($default_format == $this->getSubmoduleFormat($component, $subComponent)) {
                    return $subComponent;
                }
            }
        }

        return parent::getDefaultActivepanelSubmodule($component);
    }
}
