<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\MutationResolvers;

class UpdateGenericCategoryTermMutationResolver extends AbstractMutateGenericCategoryTermMutationResolver
{
    use UpdateCategoryTermMutationResolverTrait;
}
