<?php

declare(strict_types=1);

namespace PoPSitesWassup\PostMutations\MutationResolvers;

use PoPSchema\CustomPostMutations\MutationResolvers\CreateCustomPostMutationResolverTrait;

class CreatePostMutationResolver extends AbstractCreateUpdatePostMutationResolver
{
    use CreateCustomPostMutationResolverTrait;
}
