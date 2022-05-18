<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\CustomPostMutations\MutationResolvers\AbstractCreateUpdateCustomPostMutationResolver;
use PoPSitesWassup\CustomPostMutations\MutationResolverBridges\AbstractCreateUpdateCustomPostMutationResolverBridge;

class PoP_AddPostLinks_DataLoad_ActionExecuter_Hook
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            AbstractCreateUpdateCustomPostMutationResolverBridge::HOOK_FORM_DATA_CREATE_OR_UPDATE,
            $this->getFormData(...),
            10
        );
        \PoP\Root\App::addAction(
            AbstractCreateUpdateCustomPostMutationResolver::HOOK_VALIDATE_CONTENT,
            $this->validateContent(...),
            10,
            2
        );
        \PoP\Root\App::addAction(
            AbstractCreateUpdateCustomPostMutationResolver::HOOK_EXECUTE_CREATE_OR_UPDATE,
            $this->createUpdate(...),
            10,
            2
        );
    }

    public function validateContent($errors_in_array, $form_data)
    {
        $errors = &$errors_in_array[0];

        if ($link = $form_data['link']) {
            if (!isValidUrl($link)) {
                // @todo Migrate from string to FeedbackItemProvider
                // $errors[] = new FeedbackItemResolution(
                //     MutationErrorFeedbackItemProvider::class,
                //     MutationErrorFeedbackItemProvider::E1,
                // );
                $errors[] = TranslationAPIFacade::getInstance()->__('The external link has an invalid format', 'pop-addpostlinks');
            }
        }
    }

    public function createUpdate($post_id, $form_data)
    {

        // Save the link in the post meta
        $link = $form_data['link'];
        if ($link) {
            \PoPCMSSchema\CustomPostMeta\Utils::updateCustomPostMeta($post_id, GD_METAKEY_POST_LINK, $link, true);
        } else {
            \PoPCMSSchema\CustomPostMeta\Utils::deleteCustomPostMeta($post_id, GD_METAKEY_POST_LINK);
        }
    }

    public function getFormData($form_data)
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $form_data['link'] = $componentprocessor_manager->getProcessor([PoP_AddPostLinks_Module_Processor_TextFormInputs::class, PoP_AddPostLinks_Module_Processor_TextFormInputs::MODULE_ADDPOSTLINKS_FORMINPUT_LINK])->getValue([PoP_AddPostLinks_Module_Processor_TextFormInputs::class, PoP_AddPostLinks_Module_Processor_TextFormInputs::MODULE_ADDPOSTLINKS_FORMINPUT_LINK]);

        return $form_data;
    }
}

/**
 * Initialize
 */
new PoP_AddPostLinks_DataLoad_ActionExecuter_Hook();
