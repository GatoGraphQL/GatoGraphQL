<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolverBridges;

use PoP\GraphQLParser\Spec\Parser\Ast\WithArgumentsInterface;
use PoP\Root\App;
use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;

abstract class AbstractUpdateUserMetaValueMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    abstract protected function getRequestKey();

    public function addArgumentsForMutation(WithArgumentsInterface $withArgumentsAST): void
    {
        $withArgumentsAST->addArgument(new \PoP\GraphQLParser\Spec\Parser\Ast\Argument('target_id', App::query($this->getRequestKey()), \PoP\GraphQLParser\StaticHelpers\LocationHelper::getNonSpecificLocation()));
    }
}
