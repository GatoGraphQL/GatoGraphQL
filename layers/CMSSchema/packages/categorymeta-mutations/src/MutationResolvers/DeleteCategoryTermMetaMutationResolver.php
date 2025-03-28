<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMetaMutations\MutationResolvers;

use PoPCMSSchema\CategoryMetaMutations\MutationResolvers\DeleteCategoryTermMetaMutationResolverTrait;

class DeleteCategoryTermMetaMutationResolver extends AbstractMutateCategoryTermMetaMutationResolver
{
    use DeleteCategoryTermMetaMutationResolverTrait;
}
