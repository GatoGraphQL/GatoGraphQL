<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoPCMSSchema\CustomPostMutations\MutationResolvers\AbstractCreateUpdateCustomPostMutationResolver;

if (class_exists('coauthors_plus')) {
    \PoP\Root\App::addAction(AbstractCreateUpdateCustomPostMutationResolver::HOOK_EXECUTE_CREATE_OR_UPDATE, 'gdCapSharewithprofiles', 10);
}
function gdCapSharewithprofiles($post_id)
{
    $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

    // Was the Share With Profiles field added to the form?
    $coauthors = $moduleprocessor_manager->getProcessor([GD_CAP_Module_Processor_UserSelectableTypeaheadFormInputs::class, GD_CAP_Module_Processor_UserSelectableTypeaheadFormInputs::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_POSTCOAUTHORS])->getValue([GD_CAP_Module_Processor_UserSelectableTypeaheadFormInputs::class, GD_CAP_Module_Processor_UserSelectableTypeaheadFormInputs::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_POSTCOAUTHORS]);
    $pluginapi = PoP_AddCoauthors_APIFactory::getInstance();
    $pluginapi->addCoauthors($post_id, $coauthors);
}
