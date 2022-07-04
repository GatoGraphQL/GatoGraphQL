<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;
use PoP\Root\App;

abstract class AbstractUpdateUserMetaValueMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    abstract protected function getRequestKey();

    public function appendMutationDataToFieldDataProvider(\PoP\ComponentModel\Mutation\FieldDataProviderInterface $fieldDataProvider): void
    {
        $fieldDataProvider->add('target_id', App::query($this->getRequestKey()));
    }
}
