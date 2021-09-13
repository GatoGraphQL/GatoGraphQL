<?php

declare(strict_types=1);

namespace PoPSchema\PostCategories\ComponentContracts;

use PoPSchema\Categories\TypeAPIs\CategoryTypeAPIInterface;
use PoPSchema\PostCategories\TypeResolvers\ObjectType\PostCategoryObjectTypeResolver;
use PoPSchema\PostCategories\Facades\PostCategoryTypeAPIFacade;

trait PostCategoryAPISatisfiedContractTrait
{
    protected function getCategoryTypeAPI(): CategoryTypeAPIInterface
    {
        return PostCategoryTypeAPIFacade::getInstance();
    }

    protected function getCategoryTypeResolverClass(): string
    {
        return PostCategoryObjectTypeResolver::class;
    }
}
