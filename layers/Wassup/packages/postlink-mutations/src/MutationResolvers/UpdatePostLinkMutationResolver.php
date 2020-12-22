<?php

declare(strict_types=1);

namespace PoPSitesWassup\PostLinkMutations\MutationResolvers;

use PoPSchema\CustomPostLinkMutations\MutationResolvers\UpdateCustomPostMutationResolverTrait;

class UpdatePostLinkMutationResolver extends AbstractCreateUpdatePostLinkMutationResolver
{
    use UpdateCustomPostMutationResolverTrait;
}
