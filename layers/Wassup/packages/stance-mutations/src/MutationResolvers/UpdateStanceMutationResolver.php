<?php

declare(strict_types=1);

namespace PoPSitesWassup\StanceMutations\MutationResolvers;

use PoPSchema\CustomPostMutations\MutationResolvers\UpdateCustomPostMutationResolverTrait;

class UpdateStanceMutationResolver extends AbstractCreateUpdateStanceMutationResolver
{
    use UpdateCustomPostMutationResolverTrait;
}
