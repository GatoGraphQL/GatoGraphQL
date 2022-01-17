<?php

declare(strict_types=1);

namespace PoPSitesWassup\PostLinkMutations\MutationResolvers;

use PoPCMSSchema\CustomPostMutations\MutationResolvers\CreateCustomPostMutationResolverTrait;

class CreatePostLinkMutationResolver extends AbstractCreateUpdatePostLinkMutationResolver
{
    use CreateCustomPostMutationResolverTrait;
}
