<?php

declare(strict_types=1);

namespace PoPSitesWassup\EventLinkMutations\MutationResolvers;

use PoPSchema\CustomPostMutations\MutationResolvers\UpdateCustomPostMutationResolverTrait;

class UpdateEventLinkMutationResolver extends AbstractCreateUpdateEventLinkMutationResolver
{
    use UpdateCustomPostMutationResolverTrait;
}
