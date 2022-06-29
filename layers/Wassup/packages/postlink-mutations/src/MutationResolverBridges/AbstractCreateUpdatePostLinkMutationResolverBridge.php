<?php

declare(strict_types=1);

namespace PoPSitesWassup\PostLinkMutations\MutationResolverBridges;

use PoP\GraphQLParser\Spec\Parser\Ast\WithArgumentsInterface;
use PoPSitesWassup\PostMutations\MutationResolverBridges\AbstractCreateUpdatePostMutationResolverBridge;

abstract class AbstractCreateUpdatePostLinkMutationResolverBridge extends AbstractCreateUpdatePostMutationResolverBridge
{
    public function addArgumentsForMutation(WithArgumentsInterface $withArgumentsAST): void
    {
        parent::addArgumentsForMutation($withArgumentsAST);

        if (PoP_ApplicationProcessors_Utils::addLinkAccesstype()) {
            $withArgumentsAST->addArgument(new \PoP\GraphQLParser\Spec\Parser\Ast\Argument('linkaccess', $this->getComponentProcessorManager()->getComponentProcessor([PoP_Module_Processor_CreateUpdatePostSelectFormInputs::class, PoP_Module_Processor_CreateUpdatePostSelectFormInputs::COMPONENT_CONTENTPOSTLINKS_FORMINPUT_LINKACCESS])->getValue([PoP_Module_Processor_CreateUpdatePostSelectFormInputs::class, PoP_Module_Processor_CreateUpdatePostSelectFormInputs::COMPONENT_CONTENTPOSTLINKS_FORMINPUT_LINKACCESS]), \PoP\GraphQLParser\StaticHelpers\LocationHelper::getNonSpecificLocation()));
        }
    }

    protected function getEditorInput()
    {
        return [PoP_Module_Processor_CreateUpdatePostTextFormInputs::class, PoP_Module_Processor_CreateUpdatePostTextFormInputs::COMPONENT_CONTENTPOSTLINKS_FORMINPUT_LINK];
    }
}
