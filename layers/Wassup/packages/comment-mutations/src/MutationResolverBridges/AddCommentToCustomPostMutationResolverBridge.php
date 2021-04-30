<?php

declare(strict_types=1);

namespace PoPSitesWassup\CommentMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;
use PoPSchema\CommentMutations\MutationResolvers\AddCommentToCustomPostMutationResolver;
use PoPSchema\CommentMutations\MutationResolvers\MutationInputProperties;

class AddCommentToCustomPostMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    public function getMutationResolverClass(): string
    {
        return AddCommentToCustomPostMutationResolver::class;
    }

    public function getFormData(): array
    {
        $form_data = array(
            MutationInputProperties::COMMENT => $this->moduleProcessorManager->getProcessor([PoP_Module_Processor_CommentEditorFormInputs::class, PoP_Module_Processor_CommentEditorFormInputs::MODULE_FORMINPUT_COMMENTEDITOR])->getValue([PoP_Module_Processor_CommentEditorFormInputs::class, PoP_Module_Processor_CommentEditorFormInputs::MODULE_FORMINPUT_COMMENTEDITOR]),
            MutationInputProperties::PARENT_COMMENT_ID => $this->moduleProcessorManager->getProcessor([PoP_Application_Module_Processor_CommentTriggerLayoutFormComponentValues::class, PoP_Application_Module_Processor_CommentTriggerLayoutFormComponentValues::MODULE_FORMCOMPONENT_CARD_COMMENT])->getValue([PoP_Application_Module_Processor_CommentTriggerLayoutFormComponentValues::class, PoP_Application_Module_Processor_CommentTriggerLayoutFormComponentValues::MODULE_FORMCOMPONENT_CARD_COMMENT]),
            MutationInputProperties::CUSTOMPOST_ID => $this->moduleProcessorManager->getProcessor([PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::MODULE_FORMCOMPONENT_CARD_COMMENTPOST])->getValue([PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::MODULE_FORMCOMPONENT_CARD_COMMENTPOST]),
        );

        return $form_data;
    }
}
