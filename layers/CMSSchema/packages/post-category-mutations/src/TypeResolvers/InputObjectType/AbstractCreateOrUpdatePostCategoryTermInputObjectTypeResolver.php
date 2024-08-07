<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\CategoryMutations\TypeResolvers\InputObjectType\AbstractCreateOrUpdateCategoryTermInputObjectTypeResolver;

abstract class AbstractCreateOrUpdatePostCategoryTermInputObjectTypeResolver extends AbstractCreateOrUpdateCategoryTermInputObjectTypeResolver implements UpdatePostCategoryTermInputObjectTypeResolverInterface, CreatePostCategoryTermInputObjectTypeResolverInterface
{
}
