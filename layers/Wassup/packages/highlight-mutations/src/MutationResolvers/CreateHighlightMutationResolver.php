<?php

declare(strict_types=1);

namespace PoPSitesWassup\HighlightMutations\MutationResolvers;

use PoPCMSSchema\CustomPostMutations\MutationResolvers\CreateCustomPostMutationResolverTrait;

class CreateHighlightMutationResolver extends AbstractCreateUpdateHighlightMutationResolver
{
    use CreateCustomPostMutationResolverTrait;
}
