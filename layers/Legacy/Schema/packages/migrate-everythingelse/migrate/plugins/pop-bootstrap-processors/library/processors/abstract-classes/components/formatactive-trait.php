<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\ComponentProcessors\FormattableModuleInterface;
use PoP\ComponentModel\State\ApplicationState;

trait FormatActiveTrait
{
    public function isSubmoduleActivePanel(array $componentVariation, $submodule)
    {
        return \PoP\Root\App::getState('format') == $this->getSubmoduleFormat($componentVariation, $submodule);
    }

    protected function getSubmoduleFormat(array $componentVariation, $submodule)
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        $processor = $componentprocessor_manager->getProcessor($submodule);
        if ($processor instanceof FormattableModuleInterface) {
            return $processor->getFormat($submodule);
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
            foreach ($this->getSubComponentVariations($componentVariation) as $submodule) {
                if ($default_format == $this->getSubmoduleFormat($componentVariation, $submodule)) {
                    return $submodule;
                }
            }
        }

        return parent::getDefaultActivepanelSubmodule($componentVariation);
    }
}
