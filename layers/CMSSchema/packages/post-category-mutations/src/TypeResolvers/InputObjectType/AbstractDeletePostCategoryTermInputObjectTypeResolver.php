<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\CategoryMutations\TypeResolvers\InputObjectType\AbstractDeleteCategoryTermInputObjectTypeResolver;

abstract class AbstractDeletePostCategoryTermInputObjectTypeResolver extends AbstractDeleteCategoryTermInputObjectTypeResolver implements DeletePostCategoryTermInputObjectTypeResolverInterface
{
}