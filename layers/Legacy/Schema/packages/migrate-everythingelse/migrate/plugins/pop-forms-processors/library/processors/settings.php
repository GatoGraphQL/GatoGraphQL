<?php

use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

// Override ComponentProcessorClass
$componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
$componentprocessor_manager->overrideProcessorClass(
    PoP_Posts_Module_Processor_TextFilterInputs::class,
    PoP_Module_Processor_TextFilterInputs::class,
    [
        PoP_Posts_Module_Processor_TextFilterInputs::COMPONENT_FILTERINPUT_SEARCH,
    ]
);
