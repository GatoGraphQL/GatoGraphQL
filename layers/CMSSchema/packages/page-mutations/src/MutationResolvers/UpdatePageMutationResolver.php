<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\MutationResolvers;

use PoPCMSSchema\CustomPostMutations\MutationResolvers\UpdateCustomPostMutationResolverTrait;

class UpdatePageMutationResolver extends AbstractCreateUpdatePageMutationResolver
{
    use UpdateCustomPostMutationResolverTrait;
}
