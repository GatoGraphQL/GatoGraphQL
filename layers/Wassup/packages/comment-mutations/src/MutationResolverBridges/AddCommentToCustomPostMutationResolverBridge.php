<?php

declare(strict_types=1);

namespace PoPSitesWassup\CommentMutations\MutationResolverBridges;

use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\MutationResolution\MutationResolutionManagerInterface;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;
use PoPSchema\CommentMutations\MutationResolvers\AddCommentToCustomPostMutationResolver;
use PoPSchema\CommentMutations\MutationResolvers\MutationInputProperties;

class AddCommentToCustomPostMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    public function __construct(
        HooksAPIInterface $hooksAPI,
        TranslationAPIInterface $translationAPI,
        InstanceManagerInterface $instanceManager,
        MutationResolutionManagerInterface $mutationResolutionManager,
        protected AddCommentToCustomPostMutationResolver $addCommentToCustomPostMutationResolver,
    ) {
        parent::__construct(
            $hooksAPI,
            $translationAPI,
            $instanceManager,
            $mutationResolutionManager,
        );
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->addCommentToCustomPostMutationResolver;
    }

    public function getFormData(): array
    {
        $form_data = array(
            MutationInputProperties::COMMENT => $this->moduleProcessorManager->getProcessor([\PoP_Module_Processor_CommentEditorFormInputs::class, \PoP_Module_Processor_CommentEditorFormInputs::MODULE_FORMINPUT_COMMENTEDITOR])->getValue([\PoP_Module_Processor_CommentEditorFormInputs::class, \PoP_Module_Processor_CommentEditorFormInputs::MODULE_FORMINPUT_COMMENTEDITOR]),
            MutationInputProperties::PARENT_COMMENT_ID => $this->moduleProcessorManager->getProcessor([\PoP_Application_Module_Processor_CommentTriggerLayoutFormComponentValues::class, \PoP_Application_Module_Processor_CommentTriggerLayoutFormComponentValues::MODULE_FORMCOMPONENT_CARD_COMMENT])->getValue([\PoP_Application_Module_Processor_CommentTriggerLayoutFormComponentValues::class, \PoP_Application_Module_Processor_CommentTriggerLayoutFormComponentValues::MODULE_FORMCOMPONENT_CARD_COMMENT]),
            MutationInputProperties::CUSTOMPOST_ID => $this->moduleProcessorManager->getProcessor([\PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::class, \PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::MODULE_FORMCOMPONENT_CARD_COMMENTPOST])->getValue([\PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::class, \PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::MODULE_FORMCOMPONENT_CARD_COMMENTPOST]),
        );

        return $form_data;
    }
}
