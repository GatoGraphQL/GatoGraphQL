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

    public function requiresUserState(array $componentVariation, array &$props)
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        $processor = $componentprocessor_manager->getProcessor($componentVariation);

        // Dataloading modules need to check for user state
        if ($processor->moduleLoadsData($componentVariation)) {
            // Check if the corresponding page requires state or not
            /*if ($checkpoint_configuration = $processor->getDataaccessCheckpointConfiguration($componentVariation, $props)) {

            return PoP_UserState_Utils::checkpointConfigurationRequiresUserState($checkpoint_configuration);
            }
            // Check if the corresponding page requires state or not
            else*/if ($route = $processor->getRelevantRoute($componentVariation, $props)) {
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
