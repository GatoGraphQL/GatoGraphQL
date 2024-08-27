<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\MutationResolvers;

use PoPCMSSchema\TagMutations\MutationResolvers\UpdateTagTermMutationResolverTrait;

class UpdateGenericTagTermMutationResolver extends AbstractMutateGenericTagTermMutationResolver
{
    use UpdateTagTermMutationResolverTrait;
}
