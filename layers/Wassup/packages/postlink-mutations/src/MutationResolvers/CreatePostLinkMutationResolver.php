<?php

declare(strict_types=1);

namespace PoPSitesWassup\PostLinkMutations\MutationResolvers;

use PoPSchema\CustomPostLinkMutations\MutationResolvers\CreateCustomPostMutationResolverTrait;

class CreatePostLinkMutationResolver extends AbstractCreateUpdatePostLinkMutationResolver
{
    use CreateCustomPostMutationResolverTrait;
}
