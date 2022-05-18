<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoPCMSSchema\Posts\ConditionalOnModule\Users\ComponentProcessors\FieldDataloadComponentProcessor as UserPostFieldDataloads;
use PoPCMSSchema\Posts\ComponentProcessors\FieldDataloadComponentProcessor as PostFieldDataloads;
use PoPCMSSchema\PostTags\ComponentProcessors\PostTagFieldDataloadComponentProcessor;
use PoPCMSSchema\PostTags\ComponentProcessors\TagPostFieldDataloadComponentProcessor;
use PoPCMSSchema\Users\ComponentProcessors\FieldDataloadComponentProcessor as UserFieldDataloads;

$moduleprocessor_manager = ComponentProcessorManagerFacade::getInstance();
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
    FieldDataloadComponentProcessor::class,
    PoP_Blog_Module_Processor_FieldDataloads::class,
    [
        PostTagFieldDataloadComponentProcessor::MODULE_DATALOAD_RELATIONALFIELDS_TAGLIST,
        TagPostFieldDataloadComponentProcessor::MODULE_DATALOAD_RELATIONALFIELDS_TAGPOSTLIST,
    ]
);
