<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolverBridges;

use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\Root\App;

abstract class AbstractUpdateUserMetaValueMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    abstract protected function getRequestKey();

    public function addArgumentsForMutation(\PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface $mutationField): void
    {
        $mutationField->addArgument(new Argument('target_id', new Literal(App::query($this->getRequestKey()), LocationHelper::getNonSpecificLocation()), LocationHelper::getNonSpecificLocation()));
    }
}
