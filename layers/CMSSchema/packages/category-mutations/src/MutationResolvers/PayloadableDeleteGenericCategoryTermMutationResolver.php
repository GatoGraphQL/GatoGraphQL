<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\MutationResolvers;

use PoPCMSSchema\CategoryMutations\MutationResolvers\PayloadableDeleteCategoryTermMutationResolverTrait;

class PayloadableDeleteGenericCategoryTermMutationResolver extends AbstractMutateGenericCategoryTermMutationResolver
{
    use PayloadableDeleteCategoryTermMutationResolverTrait;
}
