<?php

declare(strict_types=1);

namespace PoPSitesWassup\HighlightMutations\MutationResolvers;

use PoPSchema\CustomPostMutations\MutationResolvers\CreateCustomPostMutationResolverTrait;

class CreateHighlightMutationResolver extends AbstractCreateUpdateHighlightMutationResolver
{
    use CreateCustomPostMutationResolverTrait;
}
