<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoPCMSSchema\CustomPostMutations\MutationResolvers\AbstractCreateUpdateCustomPostMutationResolver;

if (class_exists('coauthors_plus')) {
    \PoP\Root\App::addAction(AbstractCreateUpdateCustomPostMutationResolver::HOOK_EXECUTE_CREATE_OR_UPDATE, 'gdCapSharewithprofiles', 10);
}
function gdCapSharewithprofiles($post_id)
{
    $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

    // Was the Share With Profiles field added to the form?
    $coauthors = $componentprocessor_manager->getComponentProcessor([GD_CAP_Module_Processor_UserSelectableTypeaheadFormInputs::class, GD_CAP_Module_Processor_UserSelectableTypeaheadFormInputs::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEAD_POSTCOAUTHORS])->getValue([GD_CAP_Module_Processor_UserSelectableTypeaheadFormInputs::class, GD_CAP_Module_Processor_UserSelectableTypeaheadFormInputs::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEAD_POSTCOAUTHORS]);
    $pluginapi = PoP_AddCoauthors_APIFactory::getInstance();
    $pluginapi->addCoauthors($post_id, $coauthors);
}
