<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMutations\MutationResolvers;

use PoPCMSSchema\TagMutations\MutationResolvers\DeleteTagTermMutationResolverTrait;

class DeletePostTagTermMutationResolver extends AbstractMutatePostTagTermMutationResolver
{
    use DeleteTagTermMutationResolverTrait;
}
