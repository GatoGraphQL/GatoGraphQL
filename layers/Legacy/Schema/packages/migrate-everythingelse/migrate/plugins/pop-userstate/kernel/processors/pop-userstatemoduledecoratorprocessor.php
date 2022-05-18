<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\ComponentProcessors\AbstractModuleDecoratorProcessor;
use PoP\ComponentModel\ComponentProcessors\AbstractComponentProcessor;

class PoP_UserStateModuleDecoratorProcessor extends AbstractModuleDecoratorProcessor
{

    //-------------------------------------------------
    // PROTECTED Functions
    //-------------------------------------------------

    protected function getModuledecoratorprocessorManager()
    {
        return PoP_UserStateModuleDecoratorProcessorManagerFactory::getInstance();
    }

    //-------------------------------------------------
    // PUBLIC Functions
    //-------------------------------------------------

    public function requiresUserState(array $component, array &$props)
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        $processor = $componentprocessor_manager->getProcessor($component);

        // Dataloading modules need to check for user state
        if ($processor->moduleLoadsData($component)) {
            // Check if the corresponding page requires state or not
            /*if ($checkpoint_configuration = $processor->getDataaccessCheckpointConfiguration($component, $props)) {

            return PoP_UserState_Utils::checkpointConfigurationRequiresUserState($checkpoint_configuration);
            }
            // Check if the corresponding page requires state or not
            else*/if ($route = $processor->getRelevantRoute($component, $props)) {
                return PoP_UserState_Utils::routeRequiresUserState($route);
}
        }

        return false;
    }
}

/**
 * Settings Initialization
 */
PoP_UserStateModuleDecoratorProcessorManagerFactory::getInstance()->add(AbstractComponentProcessor::class, PoP_UserStateModuleDecoratorProcessor::class);
