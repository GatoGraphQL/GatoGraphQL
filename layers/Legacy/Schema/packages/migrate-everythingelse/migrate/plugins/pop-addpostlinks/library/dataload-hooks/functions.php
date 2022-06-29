<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\GraphQLParser\Spec\Parser\Ast\WithArgumentsInterface;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\CustomPostMutations\MutationResolvers\AbstractCreateUpdateCustomPostMutationResolver;
use PoPSitesWassup\CustomPostMutations\MutationResolverBridges\AbstractCreateUpdateCustomPostMutationResolverBridge;

class PoP_AddPostLinks_DataLoad_ActionExecuter_Hook
{
    public function __construct()
    {
        \PoP\Root\App::addAction(
            AbstractCreateUpdateCustomPostMutationResolverBridge::HOOK_FORM_DATA_CREATE_OR_UPDATE,
            $this->addArgumentsForMutation(...),
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

    public function validateContent($errors_in_array, WithArgumentsInterface $withArgumentsAST)
    {
        $errors = &$errors_in_array[0];

        if ($link = $withArgumentsAST->getArgumentValue('link')) {
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

    public function createUpdate($post_id, WithArgumentsInterface $withArgumentsAST)
    {

        // Save the link in the post meta
        $link = $withArgumentsAST->getArgumentValue('link');
        if ($link) {
            \PoPCMSSchema\CustomPostMeta\Utils::updateCustomPostMeta($post_id, GD_METAKEY_POST_LINK, $link, true);
        } else {
            \PoPCMSSchema\CustomPostMeta\Utils::deleteCustomPostMeta($post_id, GD_METAKEY_POST_LINK);
        }
    }

    public function addArgumentsForMutation(WithArgumentsInterface $withArgumentsAST): void
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $withArgumentsAST->addArgument(new \PoP\GraphQLParser\Spec\Parser\Ast\Argument('link', $componentprocessor_manager->getComponentProcessor([PoP_AddPostLinks_Module_Processor_TextFormInputs::class, PoP_AddPostLinks_Module_Processor_TextFormInputs::COMPONENT_ADDPOSTLINKS_FORMINPUT_LINK])->getValue([PoP_AddPostLinks_Module_Processor_TextFormInputs::class, PoP_AddPostLinks_Module_Processor_TextFormInputs::COMPONENT_ADDPOSTLINKS_FORMINPUT_LINK]), \PoP\GraphQLParser\StaticHelpers\LocationHelper::getNonSpecificLocation()));
    }
}

/**
 * Initialize
 */
new PoP_AddPostLinks_DataLoad_ActionExecuter_Hook();
