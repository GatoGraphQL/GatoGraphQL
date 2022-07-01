<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\ComponentModel\ComponentProcessors\ComponentProcessorManagerInterface;
use PoP\ComponentModel\ComponentProcessors\FormComponentComponentProcessorInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputList;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
use PoPSitesWassup\EverythingElseMutations\MutationResolverUtils\MutationResolverUtils;

trait CreateUpdateProfileMutationResolverBridgeTrait
{
    abstract protected function getComponentProcessorManager(): ComponentProcessorManagerInterface;

    protected function getUsercommunitiesFormData(FieldInterface $mutationField)
    {
        $inputs = MutationResolverUtils::getMyCommunityFormInputs();
        /** @var FormComponentComponentProcessorInterface */
        $componentProcessor = $this->getComponentProcessorManager()->getComponentProcessor($inputs['communities']);
        $communities = $componentProcessor->getValue($inputs['communities']);
        $mutationDataProvider->add('communities', $communities ?? array());
    }
}
