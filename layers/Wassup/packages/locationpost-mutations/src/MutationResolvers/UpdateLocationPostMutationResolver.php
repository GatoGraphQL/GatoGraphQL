<?php

declare(strict_types=1);

namespace PoPSitesWassup\LocationPostMutations\MutationResolvers;

use PoPSchema\CustomPostMutations\MutationResolvers\UpdateCustomPostMutationResolverTrait;

class UpdateLocationPostMutationResolver extends AbstractCreateUpdateLocationPostMutationResolver
{
    use UpdateCustomPostMutationResolverTrait;
}
