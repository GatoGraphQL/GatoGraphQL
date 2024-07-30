<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\MutationResolvers;

use PoPCMSSchema\CategoryMutations\MutationResolvers\PayloadableUpdateCategoryMutationResolverTrait;

class PayloadableUpdateGenericCategoryMutationResolver extends AbstractCreateUpdateGenericCategoryMutationResolver
{
    use PayloadableUpdateCategoryMutationResolverTrait;
}
