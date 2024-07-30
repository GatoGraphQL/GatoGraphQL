<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\MutationResolvers;

use PoPCMSSchema\CategoryMutations\MutationResolvers\PayloadableCreateCategoryMutationResolverTrait;

class PayloadableCreateGenericCategoryMutationResolver extends AbstractCreateUpdateGenericCategoryMutationResolver
{
    use PayloadableCreateCategoryMutationResolverTrait;
}
