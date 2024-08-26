<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\MutationResolvers;

use PoPCMSSchema\TagMutations\MutationResolvers\AbstractMutateTagTermMutationResolver;

abstract class AbstractMutateGenericTagTermMutationResolver extends AbstractMutateTagTermMutationResolver
{
    public function getTaxonomyName(): string
    {
        return '';
    }
}
