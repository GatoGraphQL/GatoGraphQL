<?php

declare(strict_types=1);

namespace PoPSchema\PostMutations\MutationResolvers;

use PoPSchema\Posts\Facades\PostTypeAPIFacade;
use PoPSchema\CustomPostMutations\MutationResolvers\AbstractCreateUpdateCustomPostMutationResolver;

abstract class AbstractCreateUpdatePostMutationResolver extends AbstractCreateUpdateCustomPostMutationResolver
{
    // @TODO: Migrate when package "Categories" is completed
    // protected function getCategoryTaxonomy(): ?string
    // {
    //     return 'category';
    // }

    public function getCustomPostType(): string
    {
        $postTypeAPI = PostTypeAPIFacade::getInstance();
        return $postTypeAPI->getPostCustomPostType();
    }
}
