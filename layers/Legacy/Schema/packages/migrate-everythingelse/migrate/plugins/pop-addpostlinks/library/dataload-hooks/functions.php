<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPSchema\CustomPostMutations\MutationResolvers\AbstractCreateUpdateCustomPostMutationResolver;
use PoPSitesWassup\CustomPostMutations\MutationResolverBridges\AbstractCreateUpdateCustomPostMutationResolverBridge;

class PoP_AddPostLinks_DataLoad_ActionExecuter_Hook
{
    public function __construct()
    {
        \PoP\Root\App::getHookManager()->addFilter(
            AbstractCreateUpdateCustomPostMutationResolverBridge::HOOK_FORM_DATA_CREATE_OR_UPDATE,
            array($this, 'getFormData'),
            10
        );
        \PoP\Root\App::getHookManager()->addAction(
            AbstractCreateUpdateCustomPostMutationResolver::HOOK_VALIDATE_CONTENT,
            array($this, 'validateContent'),
            10,
            2
        );
        \PoP\Root\App::getHookManager()->addAction(
            AbstractCreateUpdateCustomPostMutationResolver::HOOK_EXECUTE_CREATE_OR_UPDATE,
            array($this, 'createUpdate'),
            10,
            2
        );
    }

    public function validateContent($errors_in_array, $form_data)
    {
        $errors = &$errors_in_array[0];

        if ($link = $form_data['link']) {
            if (!isValidUrl($link)) {
                $errors[] = TranslationAPIFacade::getInstance()->__('The external link has an invalid format', 'pop-addpostlinks');
            }
        }
    }

    public function createUpdate($post_id, $form_data)
    {

        // Save the link in the post meta
        $link = $form_data['link'];
        if ($link) {
            \PoPSchema\CustomPostMeta\Utils::updateCustomPostMeta($post_id, GD_METAKEY_POST_LINK, $link, true);
        } else {
            \PoPSchema\CustomPostMeta\Utils::deleteCustomPostMeta($post_id, GD_METAKEY_POST_LINK);
        }
    }

    public function getFormData($form_data)
    {
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        $form_data['link'] = $moduleprocessor_manager->getProcessor([PoP_AddPostLinks_Module_Processor_TextFormInputs::class, PoP_AddPostLinks_Module_Processor_TextFormInputs::MODULE_ADDPOSTLINKS_FORMINPUT_LINK])->getValue([PoP_AddPostLinks_Module_Processor_TextFormInputs::class, PoP_AddPostLinks_Module_Processor_TextFormInputs::MODULE_ADDPOSTLINKS_FORMINPUT_LINK]);

        return $form_data;
    }
}

/**
 * Initialize
 */
new PoP_AddPostLinks_DataLoad_ActionExecuter_Hook();
