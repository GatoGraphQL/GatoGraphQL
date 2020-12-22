<?php

declare(strict_types=1);

namespace PoPSitesWassup\PostMutations\MutationResolvers;

use PoPSchema\CustomPostMutations\MutationResolvers\UpdateCustomPostMutationResolverTrait;

class UpdatePostMutationResolver extends AbstractCreateUpdatePostMutationResolver
{
    use UpdateCustomPostMutationResolverTrait;
}
