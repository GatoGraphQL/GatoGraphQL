<?php

declare(strict_types=1);

namespace PoPSitesWassup\HighlightMutations\MutationResolvers;

use PoPSchema\CustomPostMutations\MutationResolvers\UpdateCustomPostMutationResolverTrait;

class UpdateHighlightMutationResolver extends AbstractCreateUpdateHighlightMutationResolver
{
    use UpdateCustomPostMutationResolverTrait;
}
