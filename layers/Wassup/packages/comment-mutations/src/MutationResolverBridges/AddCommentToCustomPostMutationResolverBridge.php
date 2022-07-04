<?php

declare(strict_types=1);

namespace PoPSitesWassup\CommentMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP_Application_Module_Processor_CommentTriggerLayoutFormComponentValues;
use PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues;
use PoP_Module_Processor_CommentEditorFormInputs;
use PoPCMSSchema\CommentMutations\MutationResolvers\AddCommentToCustomPostMutationResolver;
use PoPCMSSchema\CommentMutations\MutationResolvers\MutationInputProperties;

class AddCommentToCustomPostMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    private ?AddCommentToCustomPostMutationResolver $addCommentToCustomPostMutationResolver = null;

    final public function setAddCommentToCustomPostMutationResolver(AddCommentToCustomPostMutationResolver $addCommentToCustomPostMutationResolver): void
    {
        $this->addCommentToCustomPostMutationResolver = $addCommentToCustomPostMutationResolver;
    }
    final protected function getAddCommentToCustomPostMutationResolver(): AddCommentToCustomPostMutationResolver
    {
        return $this->addCommentToCustomPostMutationResolver ??= $this->instanceManager->getInstance(AddCommentToCustomPostMutationResolver::class);
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getAddCommentToCustomPostMutationResolver();
    }

    public function appendMutationDataToFieldDataAccessor(\PoP\ComponentModel\Mutation\FieldDataAccessorInterface $fieldDataAccessor): void
    {
        $fieldDataAccessor->add(MutationInputProperties::COMMENT, $this->getComponentProcessorManager()->getComponentProcessor([PoP_Module_Processor_CommentEditorFormInputs::class, PoP_Module_Processor_CommentEditorFormInputs::COMPONENT_FORMINPUT_COMMENTEDITOR])->getValue([PoP_Module_Processor_CommentEditorFormInputs::class, PoP_Module_Processor_CommentEditorFormInputs::COMPONENT_FORMINPUT_COMMENTEDITOR]));
        $fieldDataAccessor->add(MutationInputProperties::PARENT_COMMENT_ID, $this->getComponentProcessorManager()->getComponentProcessor([PoP_Application_Module_Processor_CommentTriggerLayoutFormComponentValues::class, PoP_Application_Module_Processor_CommentTriggerLayoutFormComponentValues::COMPONENT_FORMCOMPONENT_CARD_COMMENT])->getValue([PoP_Application_Module_Processor_CommentTriggerLayoutFormComponentValues::class, PoP_Application_Module_Processor_CommentTriggerLayoutFormComponentValues::COMPONENT_FORMCOMPONENT_CARD_COMMENT]));
        $fieldDataAccessor->add(MutationInputProperties::CUSTOMPOST_ID, $this->getComponentProcessorManager()->getComponentProcessor([PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::COMPONENT_FORMCOMPONENT_CARD_COMMENTPOST])->getValue([PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::COMPONENT_FORMCOMPONENT_CARD_COMMENTPOST]));
    }
}
