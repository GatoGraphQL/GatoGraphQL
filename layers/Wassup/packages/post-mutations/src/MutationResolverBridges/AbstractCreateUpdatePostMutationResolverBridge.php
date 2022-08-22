<?php

declare(strict_types=1);

namespace PoPSitesWassup\PostMutations\MutationResolverBridges;

use PoP_Application_Utils;
use PoPSitesWassup\CustomPostMutations\MutationResolverBridges\AbstractCreateUpdateCustomPostMutationResolverBridge;

abstract class AbstractCreateUpdatePostMutationResolverBridge extends AbstractCreateUpdateCustomPostMutationResolverBridge
{
    protected function showCategories(): bool
    {
        return !empty(PoP_Application_Utils::getContentpostsectionCats());
    }
}
