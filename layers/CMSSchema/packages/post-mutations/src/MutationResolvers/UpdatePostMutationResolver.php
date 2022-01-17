<?php

declare(strict_types=1);

namespace PoPSchema\PostMutations\MutationResolvers;

use PoPSchema\CustomPostMutations\MutationResolvers\UpdateCustomPostMutationResolverTrait;

class UpdatePostMutationResolver extends AbstractCreateUpdatePostMutationResolver
{
    use UpdateCustomPostMutationResolverTrait;
}
