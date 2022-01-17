<?php

declare(strict_types=1);

namespace PoPSitesWassup\StanceMutations\MutationResolvers;

use PoPCMSSchema\CustomPostMutations\MutationResolvers\CreateCustomPostMutationResolverTrait;

class CreateStanceMutationResolver extends AbstractCreateUpdateStanceMutationResolver
{
    use CreateCustomPostMutationResolverTrait;
}
