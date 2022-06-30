<?php

declare(strict_types=1);

namespace PoPSitesWassup\PostLinkMutations\MutationResolverBridges;

use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoPSitesWassup\PostMutations\MutationResolverBridges\AbstractCreateUpdatePostMutationResolverBridge;

abstract class AbstractCreateUpdatePostLinkMutationResolverBridge extends AbstractCreateUpdatePostMutationResolverBridge
{
    public function addArgumentsForMutation(\PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface $mutationField): void
    {
        parent::addArgumentsForMutation($mutationField);

        if (PoP_ApplicationProcessors_Utils::addLinkAccesstype()) {
            $mutationField->addArgument(new Argument('linkaccess', new Literal($this->getComponentProcessorManager()->getComponentProcessor([PoP_Module_Processor_CreateUpdatePostSelectFormInputs::class, PoP_Module_Processor_CreateUpdatePostSelectFormInputs::COMPONENT_CONTENTPOSTLINKS_FORMINPUT_LINKACCESS])->getValue([PoP_Module_Processor_CreateUpdatePostSelectFormInputs::class, PoP_Module_Processor_CreateUpdatePostSelectFormInputs::COMPONENT_CONTENTPOSTLINKS_FORMINPUT_LINKACCESS]), LocationHelper::getNonSpecificLocation()), LocationHelper::getNonSpecificLocation()));
        }
    }

    protected function getEditorInput()
    {
        return [PoP_Module_Processor_CreateUpdatePostTextFormInputs::class, PoP_Module_Processor_CreateUpdatePostTextFormInputs::COMPONENT_CONTENTPOSTLINKS_FORMINPUT_LINK];
    }
}
