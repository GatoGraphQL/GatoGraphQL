<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\ComponentProcessors\FormattableModuleInterface;
use PoP\ComponentModel\State\ApplicationState;

trait FormatActiveTrait
{
    public function isSubmoduleActivePanel(array $componentVariation, $subComponentVariation)
    {
        return \PoP\Root\App::getState('format') == $this->getSubmoduleFormat($componentVariation, $subComponentVariation);
    }

    protected function getSubmoduleFormat(array $componentVariation, $subComponentVariation)
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        $processor = $componentprocessor_manager->getProcessor($subComponentVariation);
        if ($processor instanceof FormattableModuleInterface) {
            return $processor->getFormat($subComponentVariation);
        }
    
        return null;
    }

    protected function getDefaultActivepanelFormat(array $componentVariation)
    {
        return null;
    }

    public function getDefaultActivepanelSubmodule(array $componentVariation)
    {
        if ($default_format = $this->getDefaultActivepanelFormat($componentVariation)) {
            foreach ($this->getSubComponentVariations($componentVariation) as $subComponentVariation) {
                if ($default_format == $this->getSubmoduleFormat($componentVariation, $subComponentVariation)) {
                    return $subComponentVariation;
                }
            }
        }

        return parent::getDefaultActivepanelSubmodule($componentVariation);
    }
}
