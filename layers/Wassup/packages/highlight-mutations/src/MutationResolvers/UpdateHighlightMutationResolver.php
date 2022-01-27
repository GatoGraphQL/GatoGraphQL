<?php

declare(strict_types=1);

namespace PoPSitesWassup\HighlightMutations\MutationResolvers;

use PoPCMSSchema\CustomPostMutations\MutationResolvers\UpdateCustomPostMutationResolverTrait;

class UpdateHighlightMutationResolver extends AbstractCreateUpdateHighlightMutationResolver
{
    use UpdateCustomPostMutationResolverTrait;
}
