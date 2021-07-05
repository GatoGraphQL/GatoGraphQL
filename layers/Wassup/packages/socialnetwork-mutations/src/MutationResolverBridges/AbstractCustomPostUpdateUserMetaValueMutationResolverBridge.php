<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolverBridges;

use PoPSchema\Posts\Constants\InputNames;

abstract class AbstractCustomPostUpdateUserMetaValueMutationResolverBridge extends AbstractUpdateUserMetaValueMutationResolverBridge
{
    protected function getRequestKey()
    {
        return InputNames::POST_ID;
    }
}
