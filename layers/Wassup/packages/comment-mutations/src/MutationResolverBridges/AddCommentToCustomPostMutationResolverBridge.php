<?php

declare(strict_types=1);

namespace PoPSitesWassup\CommentMutations\MutationResolverBridges;

use PoP\GraphQLParser\Spec\Parser\Ast\WithArgumentsInterface;
use PoP_Module_Processor_CommentEditorFormInputs;
use PoP_Application_Module_Processor_CommentTriggerLayoutFormComponentValues;
use PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues;
use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
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

    public function addArgumentsForMutation(WithArgumentsInterface $withArgumentsAST): void
    {
        $withArgumentsAST->addArgument(new \PoP\GraphQLParser\Spec\Parser\Ast\Argument(MutationInputProperties::COMMENT, $this->getComponentProcessorManager()->getComponentProcessor([PoP_Module_Processor_CommentEditorFormInputs::class, PoP_Module_Processor_CommentEditorFormInputs::COMPONENT_FORMINPUT_COMMENTEDITOR])->getValue([PoP_Module_Processor_CommentEditorFormInputs::class, PoP_Module_Processor_CommentEditorFormInputs::COMPONENT_FORMINPUT_COMMENTEDITOR]), \PoP\GraphQLParser\StaticHelpers\LocationHelper::getNonSpecificLocation()));
        $withArgumentsAST->addArgument(new \PoP\GraphQLParser\Spec\Parser\Ast\Argument(MutationInputProperties::PARENT_COMMENT_ID, $this->getComponentProcessorManager()->getComponentProcessor([PoP_Application_Module_Processor_CommentTriggerLayoutFormComponentValues::class, PoP_Application_Module_Processor_CommentTriggerLayoutFormComponentValues::COMPONENT_FORMCOMPONENT_CARD_COMMENT])->getValue([PoP_Application_Module_Processor_CommentTriggerLayoutFormComponentValues::class, PoP_Application_Module_Processor_CommentTriggerLayoutFormComponentValues::COMPONENT_FORMCOMPONENT_CARD_COMMENT]), \PoP\GraphQLParser\StaticHelpers\LocationHelper::getNonSpecificLocation()));
        $withArgumentsAST->addArgument(new \PoP\GraphQLParser\Spec\Parser\Ast\Argument(MutationInputProperties::CUSTOMPOST_ID, $this->getComponentProcessorManager()->getComponentProcessor([PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::COMPONENT_FORMCOMPONENT_CARD_COMMENTPOST])->getValue([PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::COMPONENT_FORMCOMPONENT_CARD_COMMENTPOST]), \PoP\GraphQLParser\StaticHelpers\LocationHelper::getNonSpecificLocation()));
    }
}
