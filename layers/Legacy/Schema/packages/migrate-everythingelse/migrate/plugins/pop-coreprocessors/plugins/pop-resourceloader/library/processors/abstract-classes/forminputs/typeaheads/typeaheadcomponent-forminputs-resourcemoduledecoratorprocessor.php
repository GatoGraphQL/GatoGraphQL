<?php

class PoP_TypeaheadComponentFormInputsResourceModuleDecoratorProcessor extends PoP_ResourceModuleDecoratorProcessor
{

    // Comment Leo 31/10/2017: the components will be rendered dynamically, yet they don't have a path, so in this case simply return true always for them
    public function isDynamicModule(array $componentVariation, array &$props)
    {
        return true;
    }
}

/**
 * Settings Initialization
 */
global $pop_resourcemoduledecoratorprocessor_manager;
$pop_resourcemoduledecoratorprocessor_manager->add(PoP_Module_Processor_TypeaheadComponentFormInputsBase::class, PoP_TypeaheadComponentFormInputsResourceModuleDecoratorProcessor::class);
