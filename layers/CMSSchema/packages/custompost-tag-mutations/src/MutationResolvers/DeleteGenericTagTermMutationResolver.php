<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\MutationResolvers;

use PoPCMSSchema\TagMutations\MutationResolvers\DeleteTagTermMutationResolverTrait;

class DeleteGenericTagTermMutationResolver extends AbstractMutateGenericTagTermMutationResolver
{
    use DeleteTagTermMutationResolverTrait;
}
