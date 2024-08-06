<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMutations\MutationResolvers;

use PoPCMSSchema\TagMutations\MutationResolvers\UpdateTagTermMutationResolverTrait;

class UpdatePostTagTermMutationResolver extends AbstractMutatePostTagTermMutationResolver
{
    use UpdateTagTermMutationResolverTrait;
}
