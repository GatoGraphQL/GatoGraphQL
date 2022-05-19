<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

// Override ComponentProcessorClass
if (!\PoP\Application\Environment::disableCustomCMSCode()) {
    $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
    $componentprocessor_manager->overrideProcessorClass(
        UserStance_Module_Processor_CustomSectionDataloads::class,
        UserStance_WP_Module_Processor_CustomSectionDataloads::class,
        [
            UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORSTANCES_CAROUSEL,
        ]
    );
}
