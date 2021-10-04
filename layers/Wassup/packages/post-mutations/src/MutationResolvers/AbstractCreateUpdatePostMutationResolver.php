<?php

declare(strict_types=1);

namespace PoPSitesWassup\PostMutations\MutationResolvers;

use PoPSchema\Posts\TypeAPIs\PostTypeAPIInterface;
use PoPSitesWassup\CustomPostMutations\MutationResolvers\AbstractCreateUpdateCustomPostMutationResolver;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractCreateUpdatePostMutationResolver extends AbstractCreateUpdateCustomPostMutationResolver
{
    protected PostTypeAPIInterface $postTypeAPI;

    #[Required]
    final public function autowireAbstractCreateUpdatePostMutationResolver(
        PostTypeAPIInterface $postTypeAPI,
    ): void {
        $this->postTypeAPI = $postTypeAPI;
    }

    public function getCustomPostType(): string
    {
        return $this->postTypeAPI->getPostCustomPostType();
    }
}
