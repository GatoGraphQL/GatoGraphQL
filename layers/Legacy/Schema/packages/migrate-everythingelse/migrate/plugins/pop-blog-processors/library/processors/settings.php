<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoPCMSSchema\Posts\ConditionalOnModule\Users\ModuleProcessors\FieldDataloadModuleProcessor as UserPostFieldDataloads;
use PoPCMSSchema\Posts\ModuleProcessors\FieldDataloadModuleProcessor as PostFieldDataloads;
use PoPCMSSchema\PostTags\ModuleProcessors\PostTagFieldDataloadModuleProcessor;
use PoPCMSSchema\PostTags\ModuleProcessors\TagPostFieldDataloadModuleProcessor;
use PoPCMSSchema\Users\ModuleProcessors\FieldDataloadModuleProcessor as UserFieldDataloads;

$moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
$moduleprocessor_manager->overrideProcessorClass(
    PostFieldDataloads::class,
    PoP_Blog_Module_Processor_FieldDataloads::class,
    [
        PostFieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_POSTLIST,
    ]
);
$moduleprocessor_manager->overrideProcessorClass(
    UserFieldDataloads::class,
    PoP_Blog_Module_Processor_FieldDataloads::class,
    [
        UserFieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_USERLIST,
    ]
);
$moduleprocessor_manager->overrideProcessorClass(
    UserPostFieldDataloads::class,
    PoP_Blog_Module_Processor_FieldDataloads::class,
    [
        UserPostFieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_AUTHORPOSTLIST,
    ]
);
$moduleprocessor_manager->overrideProcessorClass(
    FieldDataloadModuleProcessor::class,
    PoP_Blog_Module_Processor_FieldDataloads::class,
    [
        PostTagFieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_TAGLIST,
        TagPostFieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_TAGPOSTLIST,
    ]
);
