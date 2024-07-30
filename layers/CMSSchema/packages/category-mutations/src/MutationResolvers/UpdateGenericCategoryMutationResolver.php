<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\MutationResolvers;

class UpdateGenericCategoryMutationResolver extends AbstractCreateUpdateGenericCategoryMutationResolver
{
    use UpdateCategoryMutationResolverTrait;
}
