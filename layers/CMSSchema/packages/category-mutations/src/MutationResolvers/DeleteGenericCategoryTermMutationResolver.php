<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\MutationResolvers;

class DeleteGenericCategoryTermMutationResolver extends AbstractMutateGenericCategoryTermMutationResolver
{
    use DeleteCategoryTermMutationResolverTrait;
}
