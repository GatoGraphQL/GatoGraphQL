<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMutations\MutationResolvers;

use PoPCMSSchema\TagMutations\MutationResolvers\AbstractMutateTagTermMutationResolver;

abstract class AbstractMutateGenericTagTermMutationResolver extends AbstractMutateTagTermMutationResolver
{
    public function getTaxonomyName(): string
    {
        return '';
    }
}
