<?php

declare(strict_types=1);

namespace PoPSchema\PostCategories\ComponentContracts;

use PoPSchema\Categories\TypeAPIs\CategoryTypeAPIInterface;
use PoPSchema\PostCategories\TypeResolvers\PostCategoryTypeResolver;
use PoPSchema\PostCategories\Facades\PostCategoryTypeAPIFacade;

trait PostCategoryAPISatisfiedContractTrait
{
    protected function getTypeAPI(): CategoryTypeAPIInterface
    {
        return PostCategoryTypeAPIFacade::getInstance();
    }

    protected function getTypeResolverClass(): string
    {
        return PostCategoryTypeResolver::class;
    }
}
