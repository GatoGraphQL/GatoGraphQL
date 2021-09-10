<?php

declare(strict_types=1);

namespace PoPSchema\PostCategories\ComponentContracts;

use PoPSchema\Categories\TypeAPIs\CategoryTypeAPIInterface;
use PoPSchema\PostCategories\TypeResolvers\ObjectType\PostCategoryTypeResolver;
use PoPSchema\PostCategories\Facades\PostCategoryTypeAPIFacade;

trait PostCategoryAPISatisfiedContractTrait
{
    protected function getCategoryTypeAPI(): CategoryTypeAPIInterface
    {
        return PostCategoryTypeAPIFacade::getInstance();
    }

    protected function getCategoryTypeResolverClass(): string
    {
        return PostCategoryTypeResolver::class;
    }
}
