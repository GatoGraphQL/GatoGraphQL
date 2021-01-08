<?php

declare(strict_types=1);

namespace PoPSitesWassup\PostLinkMutations\MutationResolvers;

use PoPSchema\CustomPostMutations\MutationResolvers\CreateCustomPostMutationResolverTrait;

class CreatePostLinkMutationResolver extends AbstractCreateUpdatePostLinkMutationResolver
{
    use CreateCustomPostMutationResolverTrait;
}
