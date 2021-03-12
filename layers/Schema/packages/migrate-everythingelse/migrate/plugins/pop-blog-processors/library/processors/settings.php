<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoPSchema\PostTags\ModuleProcessors\TagPostFieldDataloads;
use PoPSchema\Tags\ModuleProcessors\FieldDataloads;

$moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
$moduleprocessor_manager->overrideProcessorClass(
    PoP_Posts_Module_Processor_FieldDataloads::class,
    PoP_Blog_Module_Processor_FieldDataloads::class,
    [
        PoP_Posts_Module_Processor_FieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_POSTLIST,
    ]
);
$moduleprocessor_manager->overrideProcessorClass(
    PoP_Users_Module_Processor_FieldDataloads::class,
    PoP_Blog_Module_Processor_FieldDataloads::class,
    [
        PoP_Users_Module_Processor_FieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_USERLIST,
    ]
);
$moduleprocessor_manager->overrideProcessorClass(
    PoP_Users_Posts_Module_Processor_FieldDataloads::class,
    PoP_Blog_Module_Processor_FieldDataloads::class,
    [
        PoP_Users_Posts_Module_Processor_FieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_AUTHORPOSTLIST,
    ]
);
$moduleprocessor_manager->overrideProcessorClass(
    FieldDataloads::class,
    PoP_Blog_Module_Processor_FieldDataloads::class,
    [
        FieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_TAGLIST,
        TagPostFieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_TAGPOSTLIST,
    ]
);


