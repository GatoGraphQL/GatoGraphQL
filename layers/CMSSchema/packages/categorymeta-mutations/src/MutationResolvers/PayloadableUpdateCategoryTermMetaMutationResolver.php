<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMetaMutations\MutationResolvers;

use PoPCMSSchema\CategoryMetaMutations\MutationResolvers\PayloadableUpdateCategoryTermMetaMutationResolverTrait;

class PayloadableUpdateCategoryTermMetaMutationResolver extends AbstractMutateCategoryTermMetaMutationResolver
{
    use PayloadableUpdateCategoryTermMetaMutationResolverTrait;
}
