<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolverBridges;

abstract class AbstractTagUpdateUserMetaValueMutationResolverBridge extends AbstractUpdateUserMetaValueMutationResolverBridge
{
    protected function getRequestKey()
    {
        return \POP_INPUTNAME_TAGID;
    }
}
