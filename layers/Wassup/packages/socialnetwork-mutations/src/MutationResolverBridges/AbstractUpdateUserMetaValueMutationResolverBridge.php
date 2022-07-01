<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolverBridges;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\Root\App;

abstract class AbstractUpdateUserMetaValueMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    abstract protected function getRequestKey();

    public function fillMutationDataProvider(\PoP\ComponentModel\Mutation\MutationDataProviderInterface $mutationDataProvider): void
    {
        $mutationDataProvider->add('target_id', App::query($this->getRequestKey()));
    }
}
