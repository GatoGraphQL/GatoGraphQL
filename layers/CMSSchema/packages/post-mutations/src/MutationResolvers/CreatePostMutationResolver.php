<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\MutationResolvers;

use PoPCMSSchema\CustomPostMutations\MutationResolvers\CreateCustomPostMutationResolverTrait;

class CreatePostMutationResolver extends AbstractCreateUpdatePostMutationResolver
{
    use CreateCustomPostMutationResolverTrait;
}
