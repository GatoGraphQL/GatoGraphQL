<?php

use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

// Override ComponentProcessorClass
$moduleprocessor_manager = ComponentProcessorManagerFacade::getInstance();
$moduleprocessor_manager->overrideProcessorClass(
    PoP_Posts_Module_Processor_TextFilterInputs::class,
    PoP_Module_Processor_TextFilterInputs::class,
    [
        PoP_Posts_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_SEARCH,
    ]
);
