<?php

declare(strict_types=1);

namespace PoPSchema\PostCategories\ComponentContracts;

use PoPSchema\Categories\TypeAPIs\CategoryTypeAPIInterface;
use PoPSchema\PostCategories\TypeResolvers\PostCategoryTypeResolver;

trait PostCategoryAPISatisfiedContractTrait
{
    protected function getTypeAPI(): CategoryTypeAPIInterface
    {
        $cmscategoriesapi = \PoPSchema\PostCategories\FunctionAPIFactory::getInstance();
        return $cmscategoriesapi;
    }

    protected function getTypeResolverClass(): string
    {
        return PostCategoryTypeResolver::class;
    }
}
