<?php

declare(strict_types=1);

namespace PoPSchema\PostMutations\MutationResolvers;

use Symfony\Contracts\Service\Attribute\Required;
use PoPSchema\CustomPostMutations\MutationResolvers\AbstractCreateUpdateCustomPostMutationResolver;
use PoPSchema\Posts\TypeAPIs\PostTypeAPIInterface;

abstract class AbstractCreateUpdatePostMutationResolver extends AbstractCreateUpdateCustomPostMutationResolver
{
    protected PostTypeAPIInterface $postTypeAPI;

    #[Required]
    public function autowireAbstractCreateUpdatePostMutationResolver(
        PostTypeAPIInterface $postTypeAPI,
    ): void {
        $this->postTypeAPI = $postTypeAPI;
    }

    public function getCustomPostType(): string
    {
        return $this->postTypeAPI->getPostCustomPostType();
    }
}
