<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers;

use PoPCMSSchema\CategoryMetaMutations\MutationResolvers\PayloadableUpdateCategoryTermMetaMutationResolverTrait;

class PayloadableUpdateGenericCategoryTermMetaMutationResolver extends AbstractMutateGenericCategoryTermMetaMutationResolver
{
    use PayloadableUpdateCategoryTermMetaMutationResolverTrait;
}
