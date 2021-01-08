<?php

declare(strict_types=1);

namespace PoPSitesWassup\PostLinkMutations\MutationResolvers;

use PoPSchema\CustomPostMutations\MutationResolvers\UpdateCustomPostMutationResolverTrait;
use PoPSitesWassup\PostLinkMutations\MutationResolvers\AbstractCreateUpdatePostLinkMutationResolver;

class UpdatePostLinkMutationResolver extends AbstractCreateUpdatePostLinkMutationResolver
{
    use UpdateCustomPostMutationResolverTrait;
}
