<?php

declare(strict_types=1);

namespace PoPSitesWassup\PostMutations\MutationResolverBridges;

use PoPSitesWassup\CustomPostMutations\MutationResolverBridges\AbstractCreateUpdateCustomPostMutationResolverBridge;

abstract class AbstractCreateUpdatePostMutationResolverBridge extends AbstractCreateUpdateCustomPostMutationResolverBridge
{
    protected function showCategories()
    {
        return !empty(\PoP_Application_Utils::getContentpostsectionCats());
    }
}
