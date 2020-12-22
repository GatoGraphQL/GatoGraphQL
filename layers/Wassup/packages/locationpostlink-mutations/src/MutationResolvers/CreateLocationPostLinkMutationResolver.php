<?php

declare(strict_types=1);

namespace PoPSitesWassup\LocationPostLinkMutations\MutationResolvers;

use PoPSchema\CustomPostMutations\MutationResolvers\CreateCustomPostMutationResolverTrait;

class CreateLocationPostLinkMutationResolver extends AbstractCreateUpdateLocationPostLinkMutationResolver
{
    use CreateCustomPostMutationResolverTrait;
}
